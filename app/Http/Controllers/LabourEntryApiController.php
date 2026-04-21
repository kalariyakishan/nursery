<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LabourEntry;
use App\Models\LabourEntryDetail;
use App\Models\Worker;
use Illuminate\Support\Facades\DB;

class LabourEntryApiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'attendance_type' => 'required|string',
            'wage_amount' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $entry = LabourEntry::firstOrCreate(
                ['date' => $request->date],
                ['total_workers' => 0, 'total_amount' => 0]
            );

            $worker_id = $request->worker_id;

            if (!$worker_id && !empty($request->worker_name)) {
                $worker = Worker::firstOrCreate(
                    ['name' => $request->worker_name],
                    ['default_wage' => $request->wage_amount]
                );
                $worker_id = $worker->id;
            }

            if (!$worker_id) {
                return response()->json(['error' => 'Worker ID or Name required'], 422);
            }

            // Prevent duplicate worker for same date
            if ($entry->details()->where('worker_id', $worker_id)->exists()) {
                return response()->json(['error' => 'Worker already exists for this day'], 422);
            }

            $detail = $entry->details()->create([
                'worker_id' => $worker_id,
                'attendance_type' => $request->attendance_type,
                'wage_amount' => $request->wage_amount,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'detail_id' => $detail->id,
                'worker_id' => $worker_id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'attendance_type' => 'required|string',
            'wage_amount' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $detail = LabourEntryDetail::findOrFail($id);
            $detail->update([
                'attendance_type' => $request->attendance_type,
                'wage_amount' => $request->wage_amount,
            ]);

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $detail = LabourEntryDetail::findOrFail($id);
            $detail->delete();

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
