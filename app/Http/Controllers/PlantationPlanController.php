<?php

namespace App\Http\Controllers;

use App\Models\PlantationPlan;
use App\Models\IrrigationPlan;
use App\Services\PlantationCalculatorService;
use App\Services\IrrigationCalculatorService;
use Illuminate\Http\Request;

class PlantationPlanController extends Controller
{
    protected $calculator;
    protected $irrigationCalculator;

    public function __construct(PlantationCalculatorService $calculator, IrrigationCalculatorService $irrigationCalculator)
    {
        $this->calculator = $calculator;
        $this->irrigationCalculator = $irrigationCalculator;
    }

    public function index()
    {
        $plans = PlantationPlan::with('irrigationPlan')->latest()->get();
        return view('plantation-plans.index', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'polygon_coordinates' => 'required|array|min:3',
            'method' => 'required|in:grid,zigzag,random,custom',
            'row_spacing' => 'required|numeric|min:0.1',
            'plant_spacing' => 'required|numeric|min:0.1',
        ]);

        $polygon = $request->polygon_coordinates;
        $method = $request->method;
        $rowSpacing = $request->row_spacing;
        $plantSpacing = $request->plant_spacing;

        $area = $this->calculator->calculateArea($polygon);
        
        $points = [];
        switch ($method) {
            case 'grid':
            case 'custom':
                $points = $this->calculator->generateGridPoints($polygon, $rowSpacing, $plantSpacing);
                break;
            case 'zigzag':
                $points = $this->calculator->generateZigZagPoints($polygon, $rowSpacing, $plantSpacing);
                break;
            case 'random':
                $points = $this->calculator->generateRandomPoints($polygon, $rowSpacing, $plantSpacing);
                break;
        }

        $plan = PlantationPlan::create([
            'name' => $request->name,
            'polygon_coordinates' => $polygon,
            'method' => $method,
            'row_spacing' => $rowSpacing,
            'plant_spacing' => $plantSpacing,
            'total_plants' => count($points),
            'area' => $area,
        ]);

        if ($request->enable_irrigation && $request->water_source_coordinates) {
            $pipelines = $this->irrigationCalculator->generatePipelines(
                $points, 
                $request->water_source_coordinates,
                $request->custom_main_pipeline ?? [],
                $request->pipeline_routing ?? 'auto'
            );
            
            IrrigationPlan::create([
                'plantation_plan_id' => $plan->id,
                'irrigation_type' => $request->irrigation_type ?? 'drip',
                'water_source_coordinates' => $request->water_source_coordinates,
                'main_pipeline' => $pipelines['main_pipeline'],
                'sub_pipelines' => $pipelines['sub_pipelines'],
                'total_main_pipe_length' => $pipelines['main_length'],
                'total_sub_pipe_length' => $pipelines['sub_length'],
                'drippers_per_plant' => $request->drippers_per_plant ?? 1,
                'total_drippers' => count($points) * ($request->drippers_per_plant ?? 1),
            ]);
            $plan->load('irrigationPlan');
        }

        return response()->json([
            'success' => true,
            'plan' => $plan,
            'points' => $points,
            'message' => 'Plan saved successfully'
        ]);
    }

    public function show(PlantationPlan $plantationPlan)
    {
        return response()->json($plantationPlan);
    }

    public function calculatePreview(Request $request)
    {
        $request->validate([
            'polygon_coordinates' => 'required|array|min:3',
            'method' => 'required|in:grid,zigzag,random,custom',
            'row_spacing' => 'required|numeric|min:0.1',
            'plant_spacing' => 'required|numeric|min:0.1',
        ]);

        $polygon = $request->polygon_coordinates;
        $method = $request->method;
        $rowSpacing = $request->row_spacing;
        $plantSpacing = $request->plant_spacing;

        $area = $this->calculator->calculateArea($polygon);
        
        $points = [];
        switch ($method) {
            case 'grid':
            case 'custom':
                $points = $this->calculator->generateGridPoints($polygon, $rowSpacing, $plantSpacing);
                break;
            case 'zigzag':
                $points = $this->calculator->generateZigZagPoints($polygon, $rowSpacing, $plantSpacing);
                break;
            case 'random':
                $points = $this->calculator->generateRandomPoints($polygon, $rowSpacing, $plantSpacing);
                break;
        }

        $response = [
            'area' => $area,
            'total_plants' => count($points),
            'points' => $points
        ];

        if ($request->enable_irrigation && $request->water_source_coordinates) {
            $pipelines = $this->irrigationCalculator->generatePipelines(
                $points, 
                $request->water_source_coordinates,
                $request->custom_main_pipeline ?? [],
                $request->pipeline_routing ?? 'auto'
            );
            $response['irrigation'] = [
                'main_pipeline' => $pipelines['main_pipeline'],
                'sub_pipelines' => $pipelines['sub_pipelines'],
                'total_main_pipe_length' => $pipelines['main_length'],
                'total_sub_pipe_length' => $pipelines['sub_length'],
                'total_drippers' => count($points) * ($request->drippers_per_plant ?? 1)
            ];
        }

        return response()->json($response);
    }

    public function destroy(PlantationPlan $plantationPlan)
    {
        $plantationPlan->delete();
        return response()->json(['success' => true, 'message' => 'Plan deleted successfully']);
    }

    public function exportPdf(PlantationPlan $plantationPlan)
    {
        $polygon = $plantationPlan->polygon_coordinates;
        $points = [];
        
        switch ($plantationPlan->method) {
            case 'grid':
            case 'custom':
                $points = $this->calculator->generateGridPoints($polygon, $plantationPlan->row_spacing, $plantationPlan->plant_spacing);
                break;
            case 'zigzag':
                $points = $this->calculator->generateZigZagPoints($polygon, $plantationPlan->row_spacing, $plantationPlan->plant_spacing);
                break;
            case 'random':
                $points = $this->calculator->generateRandomPoints($polygon, $plantationPlan->row_spacing, $plantationPlan->plant_spacing);
                break;
        }

        // Generate Google Static Maps URL
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $polygonStr = '';
        foreach ($polygon as $p) {
            $polygonStr .= $p['lat'] . ',' . $p['lng'] . '|';
        }
        // close the polygon
        $polygonStr .= $polygon[0]['lat'] . ',' . $polygon[0]['lng'];
        
        $pathStr = "color:0x4338CA|weight:2|fillcolor:0x4F46E533|" . $polygonStr;
        
        // Add water source if exists
        $waterSourceStr = "";
        if ($plantationPlan->irrigationPlan && $plantationPlan->irrigationPlan->water_source_coordinates) {
            $ws = $plantationPlan->irrigationPlan->water_source_coordinates;
            $waterSourceStr = "&markers=color:blue|label:W|" . $ws['lat'] . "," . $ws['lng'];
        }

        $markersStr = "size:tiny|color:0x10B981|";
        $sampleRate = max(1, ceil(count($points) / 200)); // Google static map URL limit ~8KB
        foreach ($points as $index => $point) {
            if ($index % $sampleRate === 0) {
                $markersStr .= $point['lat'] . ',' . $point['lng'] . '|';
            }
        }
        $markersStr = rtrim($markersStr, '|');

        $mapUrl = "https://maps.googleapis.com/maps/api/staticmap?size=640x400&path={$pathStr}&markers={$markersStr}{$waterSourceStr}&key={$apiKey}&maptype=hybrid";
        
        // Fetch image and encode to base64 to avoid DOMPDF network issues
        $mapBase64 = '';
        try {
            $mapImage = file_get_contents($mapUrl);
            if ($mapImage) {
                $mapBase64 = 'data:image/png;base64,' . base64_encode($mapImage);
            }
        } catch (\Exception $e) {
            // Ignore if map fails to load
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('plantation-plans.pdf', [
            'plan' => $plantationPlan,
            'mapBase64' => $mapBase64,
        ])->setOption('isPhpEnabled', true)
          ->setOption('isHtml5ParserEnabled', true);

        return $pdf->download('plantation_plan_' . $plantationPlan->id . '.pdf');
    }
}
