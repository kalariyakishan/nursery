<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Setting;
use App\Services\GstService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    protected $gstService;

    public function __construct(GstService $gstService)
    {
        $this->gstService = $gstService;
    }
    public function index()
    {
        return view('invoices.index');
    }

    public function getData(Request $request)
    {
        $query = Invoice::query();

        if ($request->filled('search_value')) {
            $search = $request->search_value;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('invoice_no', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $totalRecords = Invoice::count();
        $filteredRecords = $query->count();

        $invoices = $query->latest()
            ->skip($request->start ?? 0)
            ->take($request->length ?? 10)
            ->get();

        $data = $invoices->map(function($invoice) {
            return [
                'id' => $invoice->id,
                'invoice_no' => $invoice->invoice_no,
                'customer_name' => $invoice->customer_name,
                'phone' => $invoice->phone,
                'total' => '₹' . number_format($invoice->total, 2),
                'date' => $invoice->created_at->format('d M, Y'),
                'time' => $invoice->created_at->format('H:i A'),
                'actions' => view('invoices.actions', compact('invoice'))->render(),
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
        $gstSettings = [
            'enabled' => Setting::get('gst_enabled', '0') === '1',
            'type' => Setting::get('gst_type', 'exclusive'),
            'percentage' => (float)Setting::get('gst_percentage', 0),
            'cgst_percentage' => (float)Setting::get('cgst_percentage', 0),
            'sgst_percentage' => (float)Setting::get('sgst_percentage', 0),
        ];
        return view('invoices.create', compact('products', 'gstSettings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'items' => 'required|array|min:1',
            'invoice_date' => 'nullable|date',
            'items.*.product_name' => 'required|string',
            'items.*.height' => 'nullable|string',
            'items.*.bag_size' => 'nullable|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'subtotal' => 'required|numeric',
            'discount' => 'required|numeric',
            'gst' => 'nullable|numeric',
            'total' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // GST Integration
            $gstEnabled = Setting::get('gst_enabled', '0') === '1';
            $gstPercentage = $gstEnabled ? (float)Setting::get('gst_percentage', 0) : 0;
            $gstType = Setting::get('gst_type', 'exclusive');
            
            $gstOptions = [
                'cgst_percentage' => (float)Setting::get('cgst_percentage', 0),
                'sgst_percentage' => (float)Setting::get('sgst_percentage', 0),
            ];

            $gstDetails = $this->gstService->calculate($request->subtotal - $request->discount, $gstPercentage, $gstType, $gstOptions);

            $invoiceData = [
                'customer_name' => $request->customer_name,
                'phone' => $request->phone,
                'address' => $request->address,
                'subtotal' => $request->subtotal,
                'discount' => $request->discount,
                'gst_percentage' => $gstDetails['gst_percentage'],
                'gst_amount' => $gstDetails['gst_amount'],
                'cgst' => $gstDetails['cgst'],
                'sgst' => $gstDetails['sgst'],
                'gst_type' => $gstDetails['gst_type'],
                'total' => $gstDetails['total'],
                'notes' => $request->notes,
            ];

            if ($request->invoice_date) {
                $invoiceData['created_at'] = $request->invoice_date . ' ' . date('H:i:s');
            }

            $invoice = Invoice::create($invoiceData);

            foreach ($request->items as $item) {
                // Total for each item: price * quantity
                $itemTotal = $item['price'] * $item['quantity'];
                
                $invoice->items()->create([
                    'product_name' => $item['product_name'],
                    'height' => $item['height'] ?? null,
                    'bag_size' => $item['bag_size'] ?? null,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal,
                ]);
            }

            DB::commit();
            return redirect()->route('invoices.show',$invoice->id)->with('success', 'ઇન્વોઇસ સફળતાપૂર્વક સાચવવામાં આવ્યું.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'કંઈક ખોટું થયું: ' . $e->getMessage());
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('items');
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('items');
        $products = Product::with('variants')->get();
        $gstSettings = [
            'enabled' => Setting::get('gst_enabled', '0') === '1',
            'type' => Setting::get('gst_type', 'exclusive'),
            'percentage' => (float)Setting::get('gst_percentage', 0),
            'cgst_percentage' => (float)Setting::get('cgst_percentage', 0),
            'sgst_percentage' => (float)Setting::get('sgst_percentage', 0),
        ];
        return view('invoices.edit', compact('invoice', 'products', 'gstSettings'));
    }

    public function pdf(Request $request, Invoice $invoice)
    {
        $invoice->load('items');
        
        $paperSize = $request->query('paper_size', 'a4');
        $isPdf = true;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.show', compact('invoice', 'paperSize', 'isPdf'));
        
        switch ($paperSize) {
            case 'a5':
                $pdf->setPaper('A5', 'portrait');
                break;
            case 'letter':
                $pdf->setPaper('letter', 'portrait');
                break;
            case 'a4':
            default:
                $pdf->setPaper('A4', 'portrait');
                break;
        }

        return $pdf->stream('invoice_' . $invoice->id . '.pdf');
    }

    public function update(Request $request, Invoice $invoice)

    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'items' => 'required|array|min:1',
            'invoice_date' => 'nullable|date',
            'items.*.product_name' => 'required|string',
            'items.*.height' => 'nullable|string',
            'items.*.bag_size' => 'nullable|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'subtotal' => 'required|numeric',
            'discount' => 'required|numeric',
            'gst' => 'nullable|numeric',
            'total' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // GST Integration
            $gstEnabled = Setting::get('gst_enabled', '0') === '1';
            $gstPercentage = $gstEnabled ? (float)Setting::get('gst_percentage', 0) : 0;
            $gstType = Setting::get('gst_type', 'exclusive');
            
            $gstOptions = [
                'cgst_percentage' => (float)Setting::get('cgst_percentage', 0),
                'sgst_percentage' => (float)Setting::get('sgst_percentage', 0),
            ];

            $gstDetails = $this->gstService->calculate($request->subtotal - $request->discount, $gstPercentage, $gstType, $gstOptions);

            $invoiceData = [
                'customer_name' => $request->customer_name,
                'phone' => $request->phone,
                'address' => $request->address,
                'subtotal' => $request->subtotal,
                'discount' => $request->discount,
                'gst_percentage' => $gstDetails['gst_percentage'],
                'gst_amount' => $gstDetails['gst_amount'],
                'cgst' => $gstDetails['cgst'],
                'sgst' => $gstDetails['sgst'],
                'gst_type' => $gstDetails['gst_type'],
                'total' => $gstDetails['total'],
                'notes' => $request->notes,
            ];

            if ($request->invoice_date) {
                // If the user changed the date, keep the old time part or just update the date
                $invoiceData['created_at'] = $request->invoice_date . ' ' . $invoice->created_at->format('H:i:s');
            }

            $invoice->update($invoiceData);

            $invoice->items()->delete();
            foreach ($request->items as $item) {
                $itemTotal = $item['price'] * $item['quantity'];
                
                $invoice->items()->create([
                    'product_name' => $item['product_name'],
                    'height' => $item['height'] ?? null,
                    'bag_size' => $item['bag_size'] ?? null,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal,
                ]);
            }

            DB::commit();
            return redirect()->route('invoices.index')->with('success', 'ઇન્વોઇસ સફળતાપૂર્વક અપડેટ કરવામાં આવ્યું.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'કંઈક ખોટું થયું: ' . $e->getMessage());
        }
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'ઇન્વોઇસ સફળતાપૂર્વક કાઢી નાખવામાં આવ્યું.');
    }

    public function searchCustomers(Request $request)
    {
        $query = $request->get('query');
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $customers = Invoice::select('customer_name as name', 'phone as mobile', 'address')
            ->where('customer_name', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->groupBy('name', 'mobile', 'address')
            ->limit(10)
            ->get();

        return response()->json($customers);
    }
}
