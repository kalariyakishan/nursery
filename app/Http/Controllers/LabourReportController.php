<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Worker;
use App\Models\LabourEntryDetail;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

use App\Models\Advance;

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

        // Group by worker for settlement table
        $settlements = [];
        $relevantWorkers = $workerId ? Worker::where('id', $workerId)->get() : $workers;

        foreach ($relevantWorkers as $worker) {
            $workerEarnings = $details->where('worker_id', $worker->id);
            $workerAdvances = $advances->where('worker_id', $worker->id);
            
            if ($workerEarnings->count() > 0 || $workerAdvances->count() > 0) {
                $totalEarnings = $workerEarnings->sum('wage_amount');
                $totalAdvance = $workerAdvances->sum('amount');
                
                $settlements[] = (object)[
                    'worker' => $worker,
                    'total_days' => $workerEarnings->groupBy('labour_entry_id')->count(),
                    'total_earnings' => $totalEarnings,
                    'total_advance' => $totalAdvance,
                    'final_payable' => $totalEarnings - $totalAdvance
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

        $fileName = "labour_report_" . ($month ?: $startDate . '_to_' . $endDate) . ".csv";
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $callback = function() use($details, $advances, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            
            // Settlement Summary
            fputcsv($file, array('LABOUR SETTLEMENT SUMMARY (' . $startDate . ' to ' . $endDate . ')'));
            fputcsv($file, array('Worker Name', 'Total Days', 'Total Earnings', 'Total Advance', 'Final Payable'));
            
            $workerGroups = $details->groupBy('worker_id');
            foreach ($workerGroups as $wid => $workerEarnings) {
                $worker = $workerEarnings->first()->worker;
                $totalEarnings = $workerEarnings->sum('wage_amount');
                $totalAdvance = $advances->where('worker_id', $wid)->sum('amount');
                fputcsv($file, array(
                    $worker->name,
                    $workerEarnings->groupBy('labour_entry_id')->count(),
                    $totalEarnings,
                    $totalAdvance,
                    $totalEarnings - $totalAdvance
                ));
            }

            fputcsv($file, array(''));
            fputcsv($file, array('DETAILED EARNINGS'));
            fputcsv($file, array('Date', 'Worker Name', 'Work Type', 'Amount'));
            foreach ($details as $d) {
                fputcsv($file, array($d->entry->date, $d->worker->name, $d->work_type, $d->wage_amount));
            }

            fputcsv($file, array(''));
            fputcsv($file, array('DETAILED ADVANCES (UPAD)'));
            fputcsv($file, array('Date', 'Worker Name', 'Note', 'Amount'));
            foreach ($advances as $a) {
                fputcsv($file, array($a->date, $a->worker->name, $a->note, $a->amount));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
