<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleSheetService;
use App\Models\GoogleIntegration;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GoogleSyncController extends Controller
{
    protected GoogleSheetService $googleSheetService;

    public function __construct(GoogleSheetService $googleSheetService)
    {
        $this->googleSheetService = $googleSheetService;
    }

    public function settings()
    {
        $integration = Auth::user()->googleIntegration;
        return view('settings.google_sync', compact('integration'));
    }

    public function redirect()
    {
        return $this->googleSheetService->connect();
    }

    public function callback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()->route('google.sync.settings')->with('error', 'Google authentication was cancelled or failed.');
        }

        $client = $this->googleSheetService->getClient();
        $token = $client->fetchAccessTokenWithAuthCode($request->get('code'));

        if (isset($token['error'])) {
            return redirect()->route('google.sync.settings')->with('error', 'Failed to retrieve access token.');
        }

        $oauth2 = new \Google\Service\Oauth2($client);
        $userInfo = $oauth2->userinfo->get();

        GoogleIntegration::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'] ?? null,
                'expires_at' => now()->addSeconds($token['expires_in']),
                'google_email' => $userInfo->email,
            ]
        );

        return redirect()->route('google.sync.settings')->with('success', 'Google account connected successfully!');
    }

    public function disconnect()
    {
        $integration = Auth::user()->googleIntegration;
        
        if ($integration) {
            $client = $this->googleSheetService->getClient();
            if ($integration->access_token) {
                $client->revokeToken($integration->access_token);
            }
            $integration->delete();
        }

        return redirect()->route('google.sync.settings')->with('success', 'Google account disconnected successfully!');
    }

    public function toggleAutoSync(Request $request)
    {
        $integration = Auth::user()->googleIntegration;
        
        if (!$integration) {
            return redirect()->back()->with('error', 'Please connect a Google account first.');
        }

        $integration->update([
            'auto_sync' => $request->has('auto_sync'),
        ]);

        $status = $integration->auto_sync ? 'enabled' : 'disabled';
        return redirect()->back()->with('success', "Auto sync has been {$status}.");
    }

    public function manualSync()
    {
        $integration = Auth::user()->googleIntegration;
        
        if (!$integration) {
            return redirect()->back()->with('error', 'Please connect a Google account first.');
        }

        // Example data retrieval - you would replace this with actual data logic
        // This is a placeholder since the exact data wasn't fully specified, 
        // but it mimics the Excel export format requested.
        $data = [
            ['Date', 'Name', 'Amount', 'Status'],
            // Example rows
            // [now()->format('Y-m-d'), 'Example', 1000, 'Paid']
        ];
        
        // Let's get some basic generic data. Usually, you'd pull from models here.
        // For demonstration, we'll fetch some data if possible, or leave it empty except headers
        
        $success = $this->googleSheetService->sync($integration, 'generic', 'System Summary', $data);

        if ($success) {
            return redirect()->back()->with('success', 'Data synced to Google Sheets successfully!');
        }

        return redirect()->back()->with('error', 'Failed to sync data. Check logs for details.');
    }
    public function manualSyncRojmel()
    {
        $integration = Auth::user()->googleIntegration;
        if (!$integration) return redirect()->back()->with('error', 'Please connect Google account first.');

        $year = date('Y');
        
        $export = new \App\Exports\RojmelYearlyExport($year);
        $fileContent = \Maatwebsite\Excel\Facades\Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);
        
        $success = $this->googleSheetService->syncFile($integration, 'rojmel_yearly', 'Rojmel Yearly Report', $fileContent);

        return $success 
            ? redirect()->back()->with('success', 'Rojmel Yearly data synced with perfect formatting successfully!')
            : redirect()->back()->with('error', 'Failed to sync Rojmel data.');
    }

    public function manualSyncLabour()
    {
        $integration = Auth::user()->googleIntegration;
        if (!$integration) return redirect()->back()->with('error', 'Please connect Google account first.');

        $startDate = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d');
        
        // Exact logic from LabourReportController@exportExcel for general range
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

            $prevEarnings = \App\Models\LabourEntryDetail::where('worker_id', $w->id)
                ->whereHas('entry', function($q) use ($startDate) {
                    $q->where('date', '<', $startDate);
                })->sum('wage_amount');
            $prevAdvances = \App\Models\Advance::where('worker_id', $w->id)
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

        $rangeString = \Carbon\Carbon::parse($startDate)->format('d/m/Y') . ' થી ' . \Carbon\Carbon::parse($endDate)->format('d/m/Y');
        
        $data = [
            'settlements' => $settlements,
            'details' => $details,
            'advances' => $advances,
            'worker' => null
        ];

        $export = new \App\Exports\LabourReportExport($data, "Labour_Summary", $rangeString);
        $fileContent = \Maatwebsite\Excel\Facades\Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);
        
        $success = $this->googleSheetService->syncFile($integration, 'labour_report', 'Labour Settlement Report', $fileContent);

        return $success 
            ? redirect()->back()->with('success', 'Labour Report data synced with perfect formatting successfully!')
            : redirect()->back()->with('error', 'Failed to sync Labour data.');
    }
}
