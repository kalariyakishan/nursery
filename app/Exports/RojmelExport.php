<?php

namespace App\Exports;

use App\Models\DailyBalance;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class RojmelExport implements FromView, ShouldAutoSize, WithTitle
{
    protected $balances;
    protected $entries;
    protected $title;
    protected $dateRange;

    public function __construct($balances, $entries = [], $title = 'Rojmel Report', $dateRange = null)
    {
        $this->balances = $balances;
        $this->entries = $entries;
        $this->title = $title;
        $this->dateRange = $dateRange;
    }

    public function view(): View
    {
        return view('exports.rojmel', [
            'balances' => $this->balances,
            'entries' => $this->entries,
            'title' => $this->title,
            'dateRange' => $this->dateRange,
            'isSummarySheet' => ($this->title === 'SUMMARY')
        ]);
    }

    public function title(): string
    {
        return $this->title;
    }
}
