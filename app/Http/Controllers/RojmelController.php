<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RojmelEntry;
use App\Models\DailyBalance;
use App\Services\RojmelService;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class RojmelController extends Controller
{
    protected $service;

    public function __construct(RojmelService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        
        $entries = RojmelEntry::where('date', $date)
            ->orderBy('created_at', 'asc')
            ->get();

        $stats = $this->service->getDailyStats($date);

        return view('rojmel.index', compact('entries', 'stats', 'date'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:avak,javak',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $entry = RojmelEntry::create($validated);
            
            // Recalculate from this date forward
            $this->service->recalculateFrom($validated['date']);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'એન્ટ્રી સફળતાપૂર્વક ઉમેરાઈ!'
                ]);
            }

            return redirect()->back()->with('success', 'એન્ટ્રી સફળતાપૂર્વક ઉમેરાઈ!');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(RojmelEntry $rojmel)
    {
        try {
            DB::beginTransaction();
            
            $date = $rojmel->date->format('Y-m-d');
            $rojmel->delete();

            // Recalculate from this date forward
            $this->service->recalculateFrom($date);

            DB::commit();
            return redirect()->back()->with('success', 'એન્ટ્રી રદ કરવામાં આવી!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function report(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $balances = DailyBalance::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'asc')
            ->get();

        $summary = [
            'total_avak' => $balances->sum('total_avak'),
            'total_javak' => $balances->sum('total_javak'),
            'net_change' => $balances->sum('total_avak') - $balances->sum('total_javak'),
            'opening_balance' => $balances->first() ? $balances->first()->opening_balance : 0,
            'closing_balance' => $balances->last() ? $balances->last()->closing_balance : 0,
        ];

        return view('rojmel.report', compact('balances', 'summary', 'startDate', 'endDate'));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $date = $request->get('date');

        if ($date) {
            $entries = RojmelEntry::where('date', $date)->get();
            $stats = $this->service->getDailyStats($date);
            $pdf = Pdf::loadView('rojmel.pdf_daily', compact('entries', 'stats', 'date'));
            return $pdf->download("rojmel_{$date}.pdf");
        } else {
            $balances = DailyBalance::whereBetween('date', [$startDate, $endDate])->get();
            $pdf = Pdf::loadView('rojmel.pdf_range', compact('balances', 'startDate', 'endDate'));
            return $pdf->download("rojmel_report_{$startDate}_{$endDate}.pdf");
        }
    }
}
