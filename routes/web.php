<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Models\Invoice;
use App\Models\Product;

Route::get('/dashboard', function () {
    $stats = [
        'totalInvoices' => Invoice::count(),
        'totalSales' => Invoice::sum('total'),
        'totalPlants' => Product::count(),
        'totalCustomers' => Invoice::distinct('customer_name')->count('customer_name'),
    ];

    $recentInvoices = Invoice::latest()->limit(5)->get();
    $recentProducts = Product::with('variants')->latest()->limit(5)->get();

    return view('dashboard', compact('stats', 'recentInvoices', 'recentProducts'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('products/data', [App\Http\Controllers\ProductController::class, 'getData'])->name('products.data');
    Route::post('products/import', [App\Http\Controllers\ProductController::class, 'import'])->name('products.import');
    Route::resource('products', ProductController::class);
    Route::get('invoices/data', [InvoiceController::class, 'getData'])->name('invoices.data');
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
    Route::resource('invoices', InvoiceController::class);

    Route::get('/api/search', function (Illuminate\Http\Request $request) {
        $query = $request->get('q');
        if (!$query) return response()->json([]);

        $invoices = App\Models\Invoice::where('customer_name', 'LIKE', "%{$query}%")
            ->orWhere('id', 'LIKE', "%{$query}%")
            ->latest()
            ->limit(10)
            ->get(['id', 'customer_name', 'total']);

        return response()->json($invoices);
    })->name('api.search');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
