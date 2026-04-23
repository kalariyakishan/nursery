<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RojmelEntry;
use App\Models\DailyBalance;
use App\Services\RojmelService;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RojmelExport;
use App\Exports\RojmelYearlyExport;

class RojmelController extends Controller
{
    protected $service;

    public function __construct(RojmelService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        
        $entries = RojmelEntry::where('date', $date)
            ->orderBy('created_at', 'asc')
            ->get();

        $stats = $this->service->getDailyStats($date);

        return view('rojmel.index', compact('entries', 'stats', 'date'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:avak,javak',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $entry = RojmelEntry::create($validated);
            
            // Recalculate from this date forward
            $this->service->recalculateFrom($validated['date']);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'એન્ટ્રી સફળતાપૂર્વક ઉમેરાઈ!'
                ]);
            }

            return redirect()->back()->with('success', 'એન્ટ્રી સફળતાપૂર્વક ઉમેરાઈ!');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(RojmelEntry $rojmel)
    {
        try {
            DB::beginTransaction();
            
            $date = $rojmel->date->format('Y-m-d');
            $rojmel->delete();

            // Recalculate from this date forward
            $this->service->recalculateFrom($date);

            DB::commit();
            return redirect()->back()->with('success', 'એન્ટ્રી રદ કરવામાં આવી!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function report(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $balances = DailyBalance::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'asc')
            ->get();

        $summary = [
            'total_avak' => $balances->sum('total_avak'),
            'total_javak' => $balances->sum('total_javak'),
            'net_change' => $balances->sum('total_avak') - $balances->sum('total_javak'),
            'opening_balance' => $balances->first() ? $balances->first()->opening_balance : 0,
            'closing_balance' => $balances->last() ? $balances->last()->closing_balance : 0,
        ];

        return view('rojmel.report', compact('balances', 'summary', 'startDate', 'endDate'));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $date = $request->get('date');

        if ($date) {
            $entries = RojmelEntry::where('date', $date)->get();
            $stats = $this->service->getDailyStats($date);
            $pdf = Pdf::loadView('rojmel.pdf_daily', compact('entries', 'stats', 'date'));
            return $pdf->download("rojmel_{$date}.pdf");
        } else {
            $balances = DailyBalance::whereBetween('date', [$startDate, $endDate])->get();
            $pdf = Pdf::loadView('rojmel.pdf_range', compact('balances', 'startDate', 'endDate'));
            return $pdf->download("rojmel_report_{$startDate}_{$endDate}.pdf");
        }
    }

    public function dashboard(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);

        // Get monthly data for the selected year
        $monthlyData = DailyBalance::select(
            DB::raw('MONTH(date) as month'),
            DB::raw('YEAR(date) as year'),
            DB::raw('SUM(total_avak) as income'),
            DB::raw('SUM(total_javak) as expense')
        )
        ->whereYear('date', $year)
        ->groupBy('year', 'month')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();

        // Calculate closing balance for each month
        // We need the closing balance of the LAST DAY of each month
        $formattedMonthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $lastDayRecord = DailyBalance::whereYear('date', $year)
                ->whereMonth('date', $m)
                ->orderBy('date', 'desc')
                ->first();

            $monthData = $monthlyData->where('month', $m)->first();
            
            $formattedMonthlyData[] = [
                'month_name' => Carbon::create($year, $m, 1)->format('F'),
                'month_gujarati' => $this->getGujaratiMonth($m),
                'income' => $monthData ? $monthData->income : 0,
                'expense' => $monthData ? $monthData->expense : 0,
                'closing_balance' => $lastDayRecord ? $lastDayRecord->closing_balance : 0,
                'month_num' => $m,
            ];
        }

        $totalYearlyIncome = $monthlyData->sum('income');
        $totalYearlyExpense = $monthlyData->sum('expense');
        $currentBalance = DailyBalance::orderBy('date', 'desc')->first()->closing_balance ?? 0;

        // NEW: Detailed Transactions logic for the dashboard (Listing individual entries for better search)
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $detailEntries = RojmelEntry::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        $detailSummary = [
            'total_avak' => $detailEntries->where('type', 'avak')->sum('amount'),
            'total_javak' => $detailEntries->where('type', 'javak')->sum('amount'),
            'opening_balance' => DailyBalance::where('date', '<', $startDate)->orderBy('date', 'desc')->first()->closing_balance ?? 0,
        ];
        $detailSummary['closing_balance'] = $detailSummary['opening_balance'] + $detailSummary['total_avak'] - $detailSummary['total_javak'];

        // Category Stats for the Chart
        $categoryStats = RojmelEntry::select('category', DB::raw('SUM(amount) as total'))
            ->whereYear('date', $year)
            ->where('type', 'javak')
            ->whereNotNull('category')
            ->groupBy('category')
            ->get();

        return view('rojmel.dashboard', compact(
            'formattedMonthlyData', 'totalYearlyIncome', 'totalYearlyExpense', 'currentBalance', 'year',
            'detailEntries', 'detailSummary', 'startDate', 'endDate', 'categoryStats'
        ));
    }

    public function getCategoryStats(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        
        $stats = RojmelEntry::select('category', DB::raw('SUM(amount) as total'))
            ->whereYear('date', $year)
            ->where('type', 'javak')
            ->whereNotNull('category')
            ->groupBy('category')
            ->get();
            
        return response()->json($stats);
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

    public function exportExcel(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $year = $request->get('year');

        if ($year) {
            // Dashboard Summary Export (Multi-sheet: Summary + 12 Months)
            return Excel::download(new RojmelYearlyExport($year), "Rojmel_Complete_{$year}.xlsx");
        } else {
            // Range Report Export (Single list)
            $balances = DailyBalance::whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'asc')
                ->get();

            $entries = RojmelEntry::whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'asc')
                ->orderBy('created_at', 'asc')
                ->get()
                ->groupBy(function($item) {
                    return $item->date->format('Y-m-d');
                });

            $rangeString = Carbon::parse($startDate)->format('d/m/Y') . ' થી ' . Carbon::parse($endDate)->format('d/m/Y');
            return Excel::download(new RojmelExport($balances, $entries, "Rojmel_Report", $rangeString), "rojmel_report_{$startDate}_{$endDate}.xlsx");
        }
    }
}
