<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Advance;
use App\Models\Worker;
use Carbon\Carbon;

class AdvanceController extends Controller
{
    public function index()
    {
        $advances = Advance::with('worker')->latest('date')->paginate(15);
        $workers = Worker::orderBy('name')->get();
        return view('advances.index', compact('advances', 'workers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'worker_id' => 'required|exists:workers,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'note' => 'nullable|string|max:255',
        ]);

        Advance::create($validated);

        return redirect()->back()->with('success', 'ઉપાડ (Advance) સફળતાપૂર્વક ઉમેરાયો!');
    }

    public function update(Request $request, Advance $advance)
    {
        $validated = $request->validate([
            'worker_id' => 'required|exists:workers,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'note' => 'nullable|string|max:255',
        ]);

        $advance->update($validated);

        return redirect()->back()->with('success', 'ઉપાડની વિગત સુધારી દેવામાં આવી છે!');
    }

    public function destroy(Advance $advance)
    {
        $advance->delete();
        return redirect()->back()->with('success', 'ઉપાડની વિગત કાઢી નાખવામાં આવી!');
    }
}
