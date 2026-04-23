<?php

namespace App\Exports;

use App\Models\Worker;
use App\Models\LabourEntryDetail;
use App\Models\Advance;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LabourWorkerYearlyExport implements WithMultipleSheets
{
    protected $workerId;
    protected $year;

    public function __construct($workerId, $year)
    {
        $this->workerId = $workerId;
        $this->year = $year;
    }

    public function sheets(): array
    {
        $sheets = [];
        $worker = Worker::find($this->workerId);
        
        // 1. Summary Sheet for this worker
        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $startDate = Carbon::create($this->year, $m, 1)->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::create($this->year, $m, 1)->endOfMonth()->format('Y-m-d');

            $earnings = LabourEntryDetail::where('worker_id', $this->workerId)
                ->whereHas('entry', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate]);
                })->sum('wage_amount');

            $upad = Advance::where('worker_id', $this->workerId)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount');

            $paid = \App\Models\Settlement::where('worker_id', $this->workerId)
                ->whereBetween('settlement_date', [$startDate, $endDate])
                ->sum('paid_amount');

            // Opening Balance before THIS month
            $prevEarnings = LabourEntryDetail::where('worker_id', $this->workerId)
                ->whereHas('entry', function($q) use ($startDate) {
                    $q->where('date', '<', $startDate);
                })->sum('wage_amount');
            $prevUpad = Advance::where('worker_id', $this->workerId)
                ->where('date', '<', $startDate)
                ->sum('amount');
            $prevPaid = \App\Models\Settlement::where('worker_id', $this->workerId)
                ->where('settlement_date', '<', $startDate)
                ->sum('paid_amount');

            $openingBalance = $prevEarnings - $prevUpad - $prevPaid;

            if ($earnings > 0 || $upad > 0 || $paid > 0 || $openingBalance != 0) {
                $monthlyData[] = (object)[
                    'month_name' => $this->getGujaratiMonth($m) . ' ' . $this->year,
                    'opening_balance' => $openingBalance,
                    'earnings' => $earnings,
                    'upad' => $upad,
                    'paid' => $paid,
                    'balance' => $openingBalance + $earnings - $upad - $paid
                ];
            }
        }

        $sheets[] = new LabourSingleWorkerExport($monthlyData, 'SUMMARY', $worker, "વર્ષ: {$this->year}");

        // 2. Monthly Sheets
        for ($m = 1; $m <= 12; $m++) {
            $startDate = Carbon::create($this->year, $m, 1)->startOfMonth();
            $endDate = Carbon::create($this->year, $m, 1)->endOfMonth();

            // Opening Balance before THIS month
            $prevEarnings = LabourEntryDetail::where('worker_id', $this->workerId)
                ->whereHas('entry', function($q) use ($startDate) {
                    $q->where('date', '<', $startDate->format('Y-m-d'));
                })->sum('wage_amount');
            $prevUpad = Advance::where('worker_id', $this->workerId)
                ->where('date', '<', $startDate->format('Y-m-d'))
                ->sum('amount');
            $prevPaid = \App\Models\Settlement::where('worker_id', $this->workerId)
                ->where('settlement_date', '<', $startDate->format('Y-m-d'))
                ->sum('paid_amount');

            $openingBalance = $prevEarnings - $prevUpad - $prevPaid;

            $days = [];
            $current = $startDate->copy();
            $hasData = false;

            while ($current <= $endDate) {
                $dateStr = $current->format('Y-m-d');
                $wageDetails = LabourEntryDetail::where('worker_id', $this->workerId)
                    ->whereHas('entry', function($q) use ($dateStr) {
                        $q->where('date', $dateStr);
                    })->get();
                
                $wage = $wageDetails->sum('wage_amount');
                $workType = $wageDetails->pluck('work_type')->filter()->unique()->implode(', ');

                $upadDetails = Advance::where('worker_id', $this->workerId)
                    ->where('date', $dateStr)
                    ->get();
                
                $upad = $upadDetails->sum('amount');
                $upadNote = $upadDetails->pluck('note')->filter()->unique()->implode(', ');

                // Also check settlements (payments) on this specific day
                $daySettlements = \App\Models\Settlement::where('worker_id', $this->workerId)
                    ->whereDate('settlement_date', $dateStr)
                    ->get();
                $paid = $daySettlements->sum('paid_amount');
                if ($paid > 0) {
                    $upadNote .= ($upadNote ? ', ' : '') . 'Salary Payment (' . $daySettlements->pluck('payment_method')->unique()->implode(', ') . ')';
                }

                if ($wage > 0 || $upad > 0 || $paid > 0) {
                    $hasData = true;
                }

                $days[] = (object)[
                    'date' => $current->copy(),
                    'wage' => $wage,
                    'work_type' => $workType,
                    'upad' => $upad + $paid, // Include settlement payments as 'Upad' in the daily list
                    'upad_note' => $upadNote
                ];
                $current->addDay();
            }

            if ($hasData) {
                $monthName = $startDate->format('M.Y');
                $sheets[] = new LabourSingleWorkerExport($days, $monthName, $worker, "માસ: " . $this->getGujaratiMonth($m), $openingBalance);
            }
        }

        return $sheets;
    }

    private function getGujaratiMonth($month)
    {
        $months = [
            1 => 'જાન્યુઆરી', 2 => 'ફેબ્રુઆરી', 3 => 'માર્ચ', 4 => 'એપ્રિલ',
            5 => 'મે', 6 => 'જૂન', 7 => 'જુલાઈ', 8 => 'ઓગસ્ટ',
            9 => 'સપ્ટેમ્બર', 10 => 'ઓક્ટોબર', 11 => 'નવેમ્બર', 12 => 'ડિસેમ્બર'
        ];
        return $months[$month] ?? '';
    }
}
