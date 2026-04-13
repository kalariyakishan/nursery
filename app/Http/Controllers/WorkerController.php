<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Worker;

class WorkerController extends Controller
{
    public function index()
    {
        $workers = Worker::latest()->get();
        return view('workers.index', compact('workers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'default_wage' => 'nullable|numeric|min:0',
        ]);

        Worker::create($validated);

        return redirect()->back()->with('success', 'મજૂર સફળતાપૂર્વક ઉમેરાયો!');
    }

    public function update(Request $request, Worker $worker)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'default_wage' => 'nullable|numeric|min:0',
        ]);

        $worker->update($validated);

        return redirect()->back()->with('success', 'મજૂરની માહિતી અપડેટ થઈ!');
    }

    public function destroy(Worker $worker)
    {
        $worker->delete();
        return redirect()->back()->with('success', 'મજૂર કાઢી નાખવામાં આવ્યો!');
    }
}
