<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfferController extends Controller
{
    public function index()
    {
        return view('offers.index');
    }

    public function getData(Request $request)
    {
        $query = Offer::query();

        if ($request->filled('search_value')) {
            $search = $request->search_value;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('offer_no', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $totalRecords = Offer::count();
        $filteredRecords = $query->count();

        $offers = $query->latest()
            ->skip($request->start ?? 0)
            ->take($request->length ?? 10)
            ->get();

        $data = $offers->map(function (Offer $offer) {
            return [
                'offer_no' => $offer->offer_no,
                'customer_name' => $offer->customer_name,
                'phone' => $offer->phone,
                'total' => '₹' . number_format((float) $offer->total, 2),
                'date' => $offer->created_at->format('d M, Y'),
                'time' => $offer->created_at->format('h:i A'),
                'actions' => view('offers.actions', compact('offer'))->render(),
            ];
        });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
    }

    public function create()
    {
        $products = Product::with('variants')->get();

        return view('offers.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string',
            'reference_no' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'greeting' => 'nullable|string|max:255',
            'intro_text' => 'nullable|string',
            'offer_date' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.plant_name' => 'required|string|max:255',
            'items.*.type_of_plant' => 'nullable|string|max:255',
            'items.*.plant_size_feet' => 'nullable|string|max:255',
            'items.*.bag_size_inches' => 'nullable|string|max:255',
            'items.*.variant' => 'nullable|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.rate' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'terms' => 'nullable|string',
            'show_total' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $offerData = [
                'customer_name' => $request->customer_name,
                'phone' => $request->phone,
                'address' => $request->address,
                'reference_no' => $request->reference_no,
                'subject' => $request->subject,
                'greeting' => $request->greeting,
                'intro_text' => $request->intro_text,
                'subtotal' => $request->subtotal,
                'discount' => $request->discount ?? 0,
                'total' => $request->total,
                'terms' => $request->terms,
                'show_total' => $request->boolean('show_total', true),
            ];

            if ($request->filled('offer_date')) {
                $offerData['created_at'] = $request->offer_date . ' ' . now()->format('H:i:s');
            }

            $offer = Offer::create($offerData);

            foreach ($request->items as $item) {
                $quantity = (int) $item['quantity'];
                $rate = (float) $item['rate'];

                $offer->items()->create([
                    'plant_name' => $item['plant_name'],
                    'type_of_plant' => $item['type_of_plant'] ?? null,
                    'plant_size_feet' => $item['plant_size_feet'] ?? null,
                    'bag_size_inches' => $item['bag_size_inches'] ?? null,
                    'variant' => $item['variant'] ?? null,
                    'quantity' => $quantity,
                    'rate' => $rate,
                    'total' => $quantity * $rate,
                ]);
            }

            DB::commit();
            return redirect()->route('offers.show', $offer)->with('success', 'Offer saved successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to save offer: ' . $e->getMessage());
        }
    }

    public function show(Offer $offer)
    {
        $offer->load('items');
        return view('offers.show', compact('offer'));
    }

    public function pdf(Offer $offer)
    {
        $offer->load('items');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('offers.letter', [
            'offer' => $offer,
            'isPdf' => true,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('offer_' . $offer->id . '.pdf');
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();
        return redirect()->route('offers.index')->with('success', 'Offer deleted successfully.');
    }
}
