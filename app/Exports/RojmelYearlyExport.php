<?php

namespace App\Exports;

use App\Models\DailyBalance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RojmelYearlyExport implements WithMultipleSheets
{
    protected $year;

    public function __construct($year)
    {
        $this->year = $year;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        // 1. Add Summary Sheet
        $monthlyData = DailyBalance::select(
            DB::raw('MONTH(date) as month'),
            DB::raw('SUM(total_avak) as income'),
            DB::raw('SUM(total_javak) as expense')
        )
        ->whereYear('date', $this->year)
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->get();

        $summaryBalances = [];
        foreach ($monthlyData as $data) {
            $lastDayRecord = DailyBalance::whereYear('date', $this->year)
                ->whereMonth('date', $data->month)
                ->orderBy('date', 'desc')
                ->first();

            $summaryBalances[] = (object)[
                'month_name' => $this->getGujaratiMonth($data->month) . ' ' . $this->year,
                'income' => $data->income,
                'expense' => $data->expense,
                'closing_balance' => $lastDayRecord ? $lastDayRecord->closing_balance : 0,
                'opening_balance' => $lastDayRecord ? $lastDayRecord->opening_balance : 0,
            ];
        }

        $sheets[] = new RojmelExport($summaryBalances, [], 'SUMMARY', "01/01/{$this->year} થી 31/12/{$this->year}");

        // 2. Add Monthly Detail Sheets
        for ($m = 1; $m <= 12; $m++) {
            $balances = DailyBalance::whereYear('date', $this->year)
                ->whereMonth('date', $m)
                ->orderBy('date', 'asc')
                ->get();

            if ($balances->count() > 0) {
                // Fetch entries for this month
                $entries = \App\Models\RojmelEntry::whereYear('date', $this->year)
                    ->whereMonth('date', $m)
                    ->orderBy('date', 'asc')
                    ->orderBy('created_at', 'asc')
                    ->get()
                    ->groupBy(function($item) {
                        return $item->date->format('Y-m-d');
                    });

                $gujMonth = $this->getGujaratiMonth($m);
                $monthName = $gujMonth . ' ' . $this->year;
                $startDate = Carbon::create($this->year, $m, 1)->startOfMonth()->format('d/m/Y');
                $endDate = Carbon::create($this->year, $m, 1)->endOfMonth()->format('d/m/Y');
                $sheets[] = new RojmelExport($balances, $entries, $monthName, "{$startDate} થી {$endDate}");
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
