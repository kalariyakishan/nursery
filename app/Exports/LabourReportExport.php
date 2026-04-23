<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class LabourReportExport implements FromView, ShouldAutoSize, WithTitle
{
    protected $data;
    protected $title;
    protected $dateRange;

    public function __construct($data, $title = 'Labour Report', $dateRange = null)
    {
        $this->data = $data;
        $this->title = $title;
        $this->dateRange = $dateRange;
    }

    public function view(): View
    {
        return view('exports.labour_report', [
            'settlements' => $this->data['settlements'],
            'details' => $this->data['details'],
            'advances' => $this->data['advances'],
            'dateRange' => $this->dateRange,
            'worker' => $this->data['worker'] ?? null
        ]);
    }

    public function title(): string
    {
        return $this->title;
    }
}
