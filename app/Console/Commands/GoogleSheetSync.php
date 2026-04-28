<?php

namespace App\Console\Commands;

use App\Models\GoogleIntegration;
use App\Services\GoogleSheetService;
use Illuminate\Console\Command;

class GoogleSheetSync extends Command
{
    protected $signature = 'google:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync system data to Google Sheets for all enabled users';

    /**
     * Execute the console command.
     */
    public function handle(GoogleSheetService $service)
    {
        $this->info('Starting Google Sheets sync...');
        
        $integrations = GoogleIntegration::where('auto_sync', true)->get();
        
        if ($integrations->isEmpty()) {
            $this->info('No active integrations found for auto sync.');
            return;
        }

        foreach ($integrations as $integration) {
            $this->info("Syncing for user: {$integration->google_email}");
            
            try {
                // 1. Sync Generic Data (System Summary)
                $genericData = [
                    ['Report Type', 'Value'],
                    ['Total Sales', \App\Models\Invoice::sum('total')],
                    ['Total Workers', \App\Models\Worker::count()],
                    ['Sync Date', now()->toDateTimeString()]
                ];
                $service->sync($integration, 'generic', 'System Summary', $genericData);

                // 2. Sync Rojmel Yearly (Multi-sheet with formatting)
                $year = date('Y');
                $rojmelExport = new \App\Exports\RojmelYearlyExport($year);
                $rojmelContent = \Maatwebsite\Excel\Facades\Excel::raw($rojmelExport, \Maatwebsite\Excel\Excel::XLSX);
                $service->syncFile($integration, 'rojmel_yearly', 'Rojmel Yearly Report', $rojmelContent);

                // 3. Sync Labour Report
                $startDate = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d');
                $endDate = \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d');
                
                $earningsQuery = \App\Models\LabourEntryDetail::with('entry', 'worker')
                    ->whereHas('entry', function($q) use ($startDate, $endDate) {
                        $q->whereBetween('date', [$startDate, $endDate]);
                    });

                $advancesQuery = \App\Models\Advance::with('worker')
                    ->whereBetween('date', [$startDate, $endDate]);

                $details = $earningsQuery->get();
                $advances = $advancesQuery->get();
                $allWorkers = \App\Models\Worker::orderBy('name')->get();
                $settlements = [];
                $periodSettlements = \App\Models\Settlement::whereBetween('settlement_date', [$startDate, $endDate])->get();

                foreach ($allWorkers as $w) {
                    $workerEarnings = $details->where('worker_id', $w->id);
                    $workerAdvances = $advances->where('worker_id', $w->id);
                    $workerSettled = $periodSettlements->where('worker_id', $w->id);

                    $prevEarnings = \App\Models\LabourEntryDetail::where('worker_id', $w->id)->whereHas('entry', function($q) use ($startDate) { $q->where('date', '<', $startDate); })->sum('wage_amount');
                    $prevAdvances = \App\Models\Advance::where('worker_id', $w->id)->where('date', '<', $startDate)->sum('amount');
                    $prevSettled = \App\Models\Settlement::where('worker_id', $w->id)->where('settlement_date', '<', $startDate)->sum('paid_amount');
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

                $rangeString = \Carbon\Carbon::parse($startDate)->format('d/m/Y') . ' થી ' . \Carbon\Carbon::parse($endDate)->format('d/m/Y');
                $labourData = [
                    'settlements' => $settlements,
                    'details' => $details,
                    'advances' => $advances,
                    'worker' => null
                ];

                $labourExport = new \App\Exports\LabourReportExport($labourData, "Labour_Summary", $rangeString);
                $labourContent = \Maatwebsite\Excel\Facades\Excel::raw($labourExport, \Maatwebsite\Excel\Excel::XLSX);
                $service->syncFile($integration, 'labour_report', 'Labour Settlement Report', $labourContent);

                $integration->update(['last_synced_at' => now()]);
                $this->info("Successfully synced all reports for: {$integration->google_email}");

            } catch (\Exception $e) {
                $this->error("Failed to sync for user {$integration->google_email}: " . $e->getMessage());
                \Illuminate\Support\Facades\Log::channel('google_sync')->error("Auto sync failed for user {$integration->id}", [
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        $this->info('Google Sheets sync completed.');
    }

    private function getGujaratiMonth($month)
    {
        $months = [
            1 => 'જાન્યુઆરી', 2 => 'ફેબ્રુઆરી', 3 => 'માર્ચ', 4 => 'એપ્રિલ',
            5 => 'મે', 6 => 'જૂન', 7 => 'જુલાઈ', 8 => 'ઓગસ્ટ',
            9 => 'સપ્ટેમ્બર', 10 => 'ઓક્ટોબર', 11 => 'નવેમ્બર', 12 => 'ડિસેમ્બર'
        ];
        return $months[(int)$month] ?? '';
    }
}
