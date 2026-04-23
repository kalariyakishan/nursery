<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Worker;
use App\Models\LabourEntryDetail;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

use App\Models\Advance;
use App\Exports\LabourReportExport;
use App\Exports\LabourWorkerYearlyExport;
use Maatwebsite\Excel\Facades\Excel;

class LabourReportController extends Controller
{
    public function index(Request $request)
    {
        $workerId = $request->get('worker_id');
        $month = $request->get('month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Handle Date Logic
        if ($startDate && $endDate) {
            // Use range, but for month input display we can't easily set it if it's not a full month
            // We'll leave $month null or set it to something indicator-y
        } else if ($month) {
            $startDate = Carbon::parse($month)->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::parse($month)->endOfMonth()->format('Y-m-d');
        } else {
            $month = Carbon::now()->format('Y-m');
            $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        }
        
        // Fetch Daily Earnings
        $earningsQuery = LabourEntryDetail::with('entry', 'worker')
            ->whereHas('entry', function($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            });

        // Fetch Advances
        $advancesQuery = Advance::with('worker')
            ->whereBetween('date', [$startDate, $endDate]);

        if ($workerId) {
            $earningsQuery->where('worker_id', $workerId);
            $advancesQuery->where('worker_id', $workerId);
        }

        $details = $earningsQuery->orderBy(DB::raw('(select date from labour_entries where id = labour_entry_details.labour_entry_id)'), 'asc')->get();
        $advances = $advancesQuery->orderBy('date', 'asc')->get();
        $workers = Worker::orderBy('name')->get();

        // Fetch Settlements in this period
        $periodSettlements = \App\Models\Settlement::whereBetween('settlement_date', [$startDate, $endDate]);
        if ($workerId) {
            $periodSettlements->where('worker_id', $workerId);
        }
        $periodSettlements = $periodSettlements->get();

        // Group by worker for settlement table
        $settlements = [];
        $relevantWorkers = $workerId ? Worker::where('id', $workerId)->get() : $workers;

        foreach ($relevantWorkers as $worker) {
            $workerEarnings = $details->where('worker_id', $worker->id);
            $workerAdvances = $advances->where('worker_id', $worker->id);
            $workerSettled = $periodSettlements->where('worker_id', $worker->id);
            
            // Calculate opening balance before $startDate
            $prevEarnings = LabourEntryDetail::where('worker_id', $worker->id)
                ->whereHas('entry', function($q) use ($startDate) {
                    $q->where('date', '<', $startDate);
                })->sum('wage_amount');
            $prevAdvances = Advance::where('worker_id', $worker->id)
                ->where('date', '<', $startDate)
                ->sum('amount');
            $prevSettled = \App\Models\Settlement::where('worker_id', $worker->id)
                ->where('settlement_date', '<', $startDate)
                ->sum('paid_amount');
            
            $openingBalance = $prevEarnings - $prevAdvances - $prevSettled;

            if ($workerEarnings->count() > 0 || $workerAdvances->count() > 0 || $workerSettled->count() > 0 || $openingBalance != 0) {
                $totalEarnings = $workerEarnings->sum('wage_amount');
                $totalAdvance = $workerAdvances->sum('amount');
                $totalPaid = $workerSettled->sum('paid_amount');
                
                $settlements[] = (object)[
                    'worker' => $worker,
                    'total_days' => $workerEarnings->groupBy('labour_entry_id')->count(),
                    'opening_balance' => $openingBalance,
                    'total_earnings' => $totalEarnings,
                    'total_advance' => $totalAdvance,
                    'total_paid' => $totalPaid,
                    'final_payable' => $openingBalance + $totalEarnings - $totalAdvance - $totalPaid
                ];
            }
        }

        $stats = [
            'total_wages' => $details->sum('wage_amount'),
            'total_advance' => $advances->sum('amount'),
            'total_payable' => $details->sum('wage_amount') - $advances->sum('amount'),
            'total_days' => $details->groupBy('labour_entry_id')->count(),
        ];

        // Timeline Logic (Ledger) when worker is selected or for overall view? 
        // User said "put filters in index so ledger not needed". 
        // Ledger's main feature was the timeline with running balance for a specific worker.
        $timeline = collect();
        if ($workerId && $relevantWorkers->count() == 1) {
            $worker = $relevantWorkers->first();
            
            foreach ($details as $e) {
                $timeline->push((object)[
                    'date' => $e->entry->date,
                    'type' => 'earning',
                    'description' => 'કામ: ' . ($e->work_type ?: 'સામાન્ય'),
                    'amount' => $e->wage_amount,
                    'raw_date' => $e->entry->date
                ]);
            }

            foreach ($advances as $a) {
                $timeline->push((object)[
                    'date' => $a->date,
                    'type' => 'advance',
                    'description' => 'ઉપાડ: ' . ($a->note ?: '-'),
                    'amount' => -$a->amount,
                    'raw_date' => $a->date
                ]);
            }

            foreach ($periodSettlements as $s) {
                $timeline->push((object)[
                    'date' => $s->settlement_date,
                    'type' => 'settlement',
                    'description' => 'પગાર પતાવટ (' . $s->payment_method . ')',
                    'amount' => -$s->paid_amount,
                    'raw_date' => $s->settlement_date
                ]);
            }

            $timeline = $timeline->sortBy('raw_date');
            
            $balance = 0;
            foreach ($timeline as $item) {
                $balance += $item->amount;
                $item->running_balance = $balance;
            }
        }

        return view('reports.labour.index', compact(
            'details', 'advances', 'settlements', 'workers', 
            'stats', 'month', 'workerId', 'startDate', 'endDate', 'timeline'
        ));
    }

    public function exportPdf(Request $request)
    {
        $month = $request->get('month');
        $workerId = $request->get('worker_id');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if ($startDate && $endDate) {
            // Use range
        } else if ($month) {
            $startDate = Carbon::parse($month)->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::parse($month)->endOfMonth()->format('Y-m-d');
        } else {
            $month = Carbon::now()->format('Y-m');
            $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        }
        
        $earningsQuery = LabourEntryDetail::with('entry', 'worker')
            ->whereHas('entry', function($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            });

        $advancesQuery = Advance::with('worker')
            ->whereBetween('date', [$startDate, $endDate]);

        if ($workerId) {
            $earningsQuery->where('worker_id', $workerId);
            $advancesQuery->where('worker_id', $workerId);
        }

        $details = $earningsQuery->get();
        $advances = $advancesQuery->get();
        $worker = $workerId ? Worker::find($workerId) : null;

        $pdf = Pdf::loadView('reports.labour.pdf', compact('details', 'advances', 'month', 'worker', 'startDate', 'endDate'));
        return $pdf->download("labour_report_" . ($month ?: $startDate . '_to_' . $endDate) . ".pdf");
    }

    public function exportExcel(Request $request)
    {
        $month = $request->get('month');
        $workerId = $request->get('worker_id');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $year = $request->get('year', date('Y'));

        // If worker is selected, provide the "Rojmel style" multi-sheet yearly report
        if ($workerId) {
            $worker = Worker::find($workerId);
            return Excel::download(new LabourWorkerYearlyExport($workerId, $year), "Labour_Report_{$worker->name}_{$year}.xlsx");
        }

        // Otherwise provide general range report
        if ($startDate && $endDate) {
            // Use range
        } else if ($month) {
            $startDate = Carbon::parse($month)->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::parse($month)->endOfMonth()->format('Y-m-d');
        } else {
            $month = Carbon::now()->format('Y-m');
            $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        }
        
        $earningsQuery = LabourEntryDetail::with('entry', 'worker')
            ->whereHas('entry', function($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            });

        $advancesQuery = Advance::with('worker')
            ->whereBetween('date', [$startDate, $endDate]);

        $details = $earningsQuery->get();
        $advances = $advancesQuery->get();

        // Fetch all workers who have data OR existing balance
        $allWorkers = Worker::orderBy('name')->get();
        $settlements = [];

        // Fetch Settlements in this period
        $periodSettlements = \App\Models\Settlement::whereBetween('settlement_date', [$startDate, $endDate])->get();

        foreach ($allWorkers as $w) {
            $workerEarnings = $details->where('worker_id', $w->id);
            $workerAdvances = $advances->where('worker_id', $w->id);
            $workerSettled = $periodSettlements->where('worker_id', $w->id);

            // Calculate opening balance before $startDate
            $prevEarnings = LabourEntryDetail::where('worker_id', $w->id)
                ->whereHas('entry', function($q) use ($startDate) {
                    $q->where('date', '<', $startDate);
                })->sum('wage_amount');
            $prevAdvances = Advance::where('worker_id', $w->id)
                ->where('date', '<', $startDate)
                ->sum('amount');
            $prevSettled = \App\Models\Settlement::where('worker_id', $w->id)
                ->where('settlement_date', '<', $startDate)
                ->sum('paid_amount');
            
            $openingBalance = $prevEarnings - $prevAdvances - $prevSettled;

            if ($workerEarnings->count() > 0 || $workerAdvances->count() > 0 || $workerSettled->count() > 0 || $openingBalance != 0) {
                $totalEarnings = $workerEarnings->sum('wage_amount');
                $totalAdvance = $workerAdvances->sum('amount');
                $totalPaid = $workerSettled->sum('paid_amount');

                $settlements[] = (object)[
                    'worker' => $w,
                    'total_days' => $workerEarnings->groupBy('labour_entry_id')->count(),
                    'opening_balance' => $openingBalance,
                    'total_earnings' => $totalEarnings,
                    'total_advance' => $totalAdvance,
                    'total_paid' => $totalPaid,
                    'final_payable' => $openingBalance + $totalEarnings - $totalAdvance - $totalPaid
                ];
            }
        }

        $rangeString = Carbon::parse($startDate)->format('d/m/Y') . ' થી ' . Carbon::parse($endDate)->format('d/m/Y');
        
        $data = [
            'settlements' => $settlements,
            'details' => $details,
            'advances' => $advances,
            'worker' => null
        ];

        return Excel::download(new LabourReportExport($data, "Labour_Summary", $rangeString), "labour_summary_{$startDate}_{$endDate}.xlsx");
    }
}
