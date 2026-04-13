<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\LabourEntry;
use App\Models\LabourEntryDetail;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LabourEntryController extends Controller
{
    public function index()
    {
        $entries = LabourEntry::latest('date')->paginate(10);
        return view('labour_entries.index', compact('entries'));
    }

    public function create()
    {
        $today = date('Y-m-d');
        $todayEntry = LabourEntry::where('date', $today)->first();
        if ($todayEntry) {
            return redirect()->route('labour-entries.edit', $todayEntry);
        }

        $workers = Worker::orderBy('name')->get();
        $items = [];
        $latestEntry = LabourEntry::latest('date')->with('details.worker')->first();
        if ($latestEntry && $latestEntry->date == date('Y-m-d', strtotime('-1 day'))) {
             $latestEntry = LabourEntry::latest('date')->with('details.worker')->first();
             // Just auto-load latest if available, regardless of date, as user asked for "yesterday's" but latest is safer.
        }
        $latestEntry = LabourEntry::latest('date')->with('details.worker')->first();

        if ($latestEntry) {
            foreach ($latestEntry->details as $detail) {
                if ($detail->worker) {
                    $items[] = [
                        'worker_id' => $detail->worker_id,
                        'worker_name' => $detail->worker->name,
                        'default_wage' => $detail->worker->default_wage, // To keep track of their wage
                        'work_type' => '',
                        'attendance_type' => 'full',
                        'hours' => 0,
                        'wage_amount' => $detail->worker->default_wage,
                        'notes' => ''
                    ];
                }
            }
        }
        
        return view('labour_entries.create', compact('workers', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.wage_amount' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $entry = LabourEntry::updateOrCreate(
                ['date' => $request->date],
                ['total_workers' => count($request->items), 'total_amount' => collect($request->items)->sum('wage_amount')]
            );

            // Clear old details if updating
            $entry->details()->delete();

            foreach ($request->items as $item) {
                $worker_id = $item['worker_id'] ?? null;
                
                // Inline worker creation
                if (!$worker_id && !empty($item['worker_name'])) {
                    $worker = Worker::firstOrCreate(
                        ['name' => $item['worker_name']],
                        ['default_wage' => $item['wage_amount']]
                    );
                    $worker_id = $worker->id;
                }

                if ($worker_id) {
                    $entry->details()->create([
                        'worker_id' => $worker_id,
                        'work_type' => $item['work_type'] ?? null,
                        'attendance_type' => $item['attendance_type'] ?? 'full',
                        'hours' => $item['hours'] ?? null,
                        'wage_amount' => $item['wage_amount'],
                        'notes' => $item['notes'] ?? null,
                    ]);
                }
            }

            // Update total workers count if some were skipped (e.g. empty rows)
            $entry->update(['total_workers' => $entry->details()->count()]);

            DB::commit();
            return redirect()->route('labour-entries.index')->with('success', 'ડેઈલી મજૂરી સફળતાપૂર્વક સાચવવામાં આવી!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'કઈક ભૂલ થઈ: ' . $e->getMessage());
        }
    }

    public function edit(LabourEntry $labour_entry)
    {
        $workers = Worker::orderBy('name')->get();
        $labour_entry->load('details.worker');
        return view('labour_entries.edit', compact('labour_entry', 'workers'));
    }

    public function update(Request $request, LabourEntry $labour_entry)
    {
        return $this->store($request); // Reuse store logic as it uses updateOrCreate
    }

    public function destroy(LabourEntry $labour_entry)
    {
        $labour_entry->delete();
        return redirect()->back()->with('success', 'એન્ટ્રી કાઢી નાખવામાં આવી!');
    }

    public function duplicate($date)
    {
        $previousEntry = LabourEntry::where('date', $date)->with('details')->first();
        if (!$previousEntry) {
            return redirect()->back()->with('error', 'પાછલી કોઈ એન્ટ્રી મળી નથી.');
        }

        $workers = Worker::orderBy('name')->get();
        $newDate = Carbon::now()->format('Y-m-d');
        
        // Pass the items to the create view
        $items = $previousEntry->details;
        
        return view('labour_entries.create', compact('workers', 'items', 'newDate'));
    }
}
