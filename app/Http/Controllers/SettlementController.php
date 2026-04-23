<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Settlement;
use App\Models\Worker;
use App\Models\LabourEntryDetail;
use App\Models\Advance;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SettlementController extends Controller
{
    public function index(Request $request)
    {
        $workerId = $request->get('worker_id');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $search = $request->get('search');

        $query = Settlement::with('worker')->latest();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if ($workerId) {
            $query->where('worker_id', $workerId);
        }

        if ($startDate) {
            $query->whereDate('settlement_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('settlement_date', '<=', $endDate);
        }

        $settlements = $query->paginate(20);
        $workers = Worker::orderBy('name')->get();
        
        return view('settlements.index', compact('settlements', 'workers', 'workerId', 'startDate', 'endDate'));
    }

    public function create(Request $request)
    {
        $workerId = $request->worker_id;
        $startDate = $request->start_date ?: Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?: Carbon::now()->endOfMonth()->format('Y-m-d');
        
        $workers = Worker::orderBy('name')->get();
        $worker = $workerId ? Worker::find($workerId) : null;
        
        $unsettledEarnings = [];
        $unsettledAdvances = [];
        $stats = ['earnings' => 0, 'advances' => 0, 'payable' => 0];

        if ($worker) {
            $unsettledEarnings = LabourEntryDetail::with('entry')
                ->where('worker_id', $workerId)
                ->whereNull('settlement_id')
                ->whereHas('entry', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate]);
                })->get();

            $unsettledAdvances = Advance::where('worker_id', $workerId)
                ->whereNull('settlement_id')
                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            $stats['earnings'] = $unsettledEarnings->sum('wage_amount');
            $stats['advances'] = $unsettledAdvances->sum('amount');
            $stats['payable'] = $stats['earnings'] - $stats['advances'];
        }

        return view('settlements.create', compact('workers', 'worker', 'unsettledEarnings', 'unsettledAdvances', 'stats', 'startDate', 'endDate', 'workerId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'worker_id' => 'required|exists:workers,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'paid_amount' => 'required|numeric|min:0',
            'settlement_date' => 'required|date',
            'payment_method' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $workerId = $request->worker_id;
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            // Fetch unsettled records again to ensure consistency
            $earnings = LabourEntryDetail::where('worker_id', $workerId)
                ->whereNull('settlement_id')
                ->whereHas('entry', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate]);
                })->get();

            $advances = Advance::where('worker_id', $workerId)
                ->whereNull('settlement_id')
                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            if ($earnings->isEmpty() && $advances->isEmpty()) {
                throw new \Exception('No unsettled records found for this period.');
            }

            $totalEarnings = $earnings->sum('wage_amount');
            $totalAdvance = $advances->sum('amount');
            $payable = $totalEarnings - $totalAdvance;

            $settlement = Settlement::create([
                'worker_id' => $workerId,
                'settlement_date' => $request->settlement_date,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_earnings' => $totalEarnings,
                'total_advance' => $totalAdvance,
                'payable_amount' => $payable,
                'paid_amount' => $request->paid_amount,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            // Link records to this settlement
            LabourEntryDetail::whereIn('id', $earnings->pluck('id'))->update(['settlement_id' => $settlement->id]);
            Advance::whereIn('id', $advances->pluck('id'))->update(['settlement_id' => $settlement->id]);

            // Create Rojmel Entry
            \App\Models\RojmelEntry::create([
                'settlement_id' => $settlement->id,
                'date' => $request->settlement_date,
                'type' => 'javak',
                'amount' => $request->paid_amount,
                'category' => 'મજૂરી ખર્ચ',
                'description' => "પગાર પતાવટ: " . $settlement->worker->name . " (" . Carbon::parse($startDate)->format('d/m') . " થી " . Carbon::parse($endDate)->format('d/m') . ")",
            ]);

            DB::commit();
            return redirect()->route('settlements.index')->with('success', 'પગાર પતાવટ (Settlement) સફળતાપૂર્વક પૂર્ણ થઈ!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'ભૂલ થઈ: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Settlement $settlement)
    {
        $settlement->load('worker', 'labourDetails.entry', 'advances');
        return view('settlements.show', compact('settlement'));
    }

    public function destroy(Settlement $settlement)
    {
        try {
            DB::beginTransaction();

            // Unlink records before deleting settlement
            LabourEntryDetail::where('settlement_id', $settlement->id)->update(['settlement_id' => null]);
            Advance::where('settlement_id', $settlement->id)->update(['settlement_id' => null]);

            $settlement->delete();

            DB::commit();
            return redirect()->back()->with('success', 'સેટલમેન્ટ રદ કરવામાં આવ્યું અને રેકોર્ડ અનલોક થઈ ગયા.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'ભૂલ થઈ: ' . $e->getMessage());
        }
    }
}
