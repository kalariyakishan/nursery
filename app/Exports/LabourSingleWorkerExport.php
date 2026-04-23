<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class LabourSingleWorkerExport implements FromView, ShouldAutoSize, WithTitle
{
    protected $data;
    protected $title;
    protected $worker;
    protected $period;
    protected $openingBalance;

    public function __construct($data, $title, $worker, $period, $openingBalance = 0)
    {
        $this->data = $data;
        $this->title = $title;
        $this->worker = $worker;
        $this->period = $period;
        $this->openingBalance = $openingBalance;
    }

    public function view(): View
    {
        return view('exports.labour_worker_single', [
            'rows' => $this->data,
            'worker' => $this->worker,
            'period' => $this->period,
            'openingBalance' => $this->openingBalance,
            'isSummary' => $this->title === 'SUMMARY'
        ]);
    }

    public function title(): string
    {
        return $this->title;
    }
}
