<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index');
    }

    public function getData(Request $request)
    {
        $query = Product::with('variants');

        if ($request->filled('search_value')) {
            $search = $request->search_value;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $totalRecords = Product::count();
        $filteredRecords = $query->count();

        $products = $query->latest()
            ->skip($request->start ?? 0)
            ->take($request->length ?? 10)
            ->get();

        $data = $products->map(function($product) {
            $variantsHtml = '';
            foreach ($product->variants as $variant) {
                $variantsHtml .= '<div class="px-3 py-1 bg-white border border-border-light rounded-lg flex items-center gap-2 shadow-sm inline-flex mb-1 mr-1">
                                    <span class="text-[10px] font-bold text-primary">' . $variant->height . '</span>
                                    <span class="w-[1px] h-3 bg-border-light"></span>
                                    <span class="text-[10px] font-bold text-text-secondary">' . $variant->bag_size . '</span>
                                    <span class="text-xs font-black text-text-primary">₹' . number_format($variant->price, 0) . '</span>
                                </div>';
            }
            
            return [
                'id' => $product->id,
                'name' => $product->name,
                'variants' => $variantsHtml,
                'actions' => view('products.actions', compact('product'))->render(),
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
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'variants' => 'nullable|array',
            'variants.*.height' => 'nullable|string|max:255',
            'variants.*.bag_size' => 'nullable|string|max:255',
            'variants.*.price' => 'nullable|numeric|min:0',
        ]);

        $product = Product::create(['name' => $request->name]);

        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                if (!empty(array_filter($variant))) {
                    $product->variants()->create($variant);
                }
            }
        }

        return redirect()->route('products.index')->with('success', 'પ્રોડક્ટ સફળતાપૂર્વક ઉમેરવામાં આવી.');
    }

    public function edit(Product $product)
    {
        $product->load('variants');
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'variants' => 'nullable|array',
            'variants.*.height' => 'nullable|string|max:255',
            'variants.*.bag_size' => 'nullable|string|max:255',
            'variants.*.price' => 'nullable|numeric|min:0',
        ]);

        $product->update(['name' => $request->name]);

        // Simple approach: delete old variants and create new ones (or sync)
        $product->variants()->delete();
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                if (!empty(array_filter($variant))) {
                    $product->variants()->create($variant);
                }
            }
        }

        return redirect()->route('products.index')->with('success', 'પ્રોડક્ટ સફળતાપૂર્વક સુધારવામાં આવી.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'પ્રોડક્ટ સફળતાપૂર્વક કાઢી નાખવામાં આવી.');
    }

    public function deleteAll()
    {
        Product::query()->delete();
        return redirect()->route('products.index')->with('success', 'બધી પ્રોડક્ટ્સ સફળતાપૂર્વક કાઢી નાખવામાં આવી.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt,xlsx',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle); // Skip header

        $count = 0;
        while (($row = fgetcsv($handle)) !== false) {
            if (empty($row[0])) continue;

            $productName = trim($row[0]);
            $height = trim($row[1] ?? '');
            $bagSize = trim($row[2] ?? '');
            $price = floatval(trim($row[3] ?? 0));

            // Create or update product
            $product = Product::firstOrCreate(['name' => $productName]);

            // Create variant
            if ($height || $bagSize || $price > 0) {
                $product->variants()->create([
                    'height' => $height,
                    'bag_size' => $bagSize,
                    'price' => $price,
                ]);
            }
            $count++;
        }
        fclose($handle);

        return redirect()->route('products.index')->with('success', $count . ' પ્રોડક્ટ્સ સફળતાપૂર્વક ઈમ્પોર્ટ કરવામાં આવી.');
    }
}
