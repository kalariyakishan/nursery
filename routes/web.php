<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OfferController;
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
    Route::delete('products/delete-all', [App\Http\Controllers\ProductController::class, 'deleteAll'])->name('products.delete_all');
    Route::resource('products', ProductController::class);
    
    Route::get('invoices/data', [InvoiceController::class, 'getData'])->name('invoices.data');
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
    Route::resource('invoices', InvoiceController::class);

    Route::get('offers/data', [OfferController::class, 'getData'])->name('offers.data');
    Route::get('offers/{offer}/pdf', [OfferController::class, 'pdf'])->name('offers.pdf');
    Route::resource('offers', OfferController::class);

    Route::get('settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');

    Route::get('/api/customers/search', [App\Http\Controllers\CustomerApiController::class, 'search'])->name('customers.search');
    Route::get('/api/search', function (Illuminate\Http\Request $request) {
        $query = $request->get('q');
        if (!$query) return response()->json([]);

        $invoices = App\Models\Invoice::where('customer_name', 'LIKE', "%{$query}%")
            ->orWhere('invoice_no', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->latest()
            ->limit(10)
            ->get(['id', 'invoice_no', 'customer_name', 'total']);

        return response()->json($invoices);
    })->name('api.search');

    Route::resource('workers', App\Http\Controllers\WorkerController::class);
    
    Route::get('labour-entries/data', [App\Http\Controllers\LabourEntryController::class, 'getData'])->name('labour-entries.data');
    Route::get('labour-entries/duplicate/{date}', [App\Http\Controllers\LabourEntryController::class, 'duplicate'])->name('labour-entries.duplicate');
    Route::resource('labour-entries', App\Http\Controllers\LabourEntryController::class);
    
    Route::get('labour-entries/api/get-by-date', [App\Http\Controllers\LabourEntryApiController::class, 'getByDate'])->name('api.labour-entries.get-by-date');
    Route::post('labour-entries/api/store', [App\Http\Controllers\LabourEntryApiController::class, 'store'])->name('api.labour-entries.store');
    Route::put('labour-entries/api/update/{id}', [App\Http\Controllers\LabourEntryApiController::class, 'update'])->name('api.labour-entries.update');
    Route::delete('labour-entries/api/destroy/{id}', [App\Http\Controllers\LabourEntryApiController::class, 'destroy'])->name('api.labour-entries.destroy');
    Route::resource('advances', App\Http\Controllers\AdvanceController::class);
    Route::resource('settlements', App\Http\Controllers\SettlementController::class);

    Route::get('reports/labour', [App\Http\Controllers\LabourReportController::class, 'index'])->name('reports.labour');
    Route::get('reports/labour/export/pdf', [App\Http\Controllers\LabourReportController::class, 'exportPdf'])->name('reports.labour.pdf');
    Route::get('reports/labour/export/excel', [App\Http\Controllers\LabourReportController::class, 'exportExcel'])->name('reports.labour.excel');

    // Rojmel Routes
    Route::get('rojmel/dashboard', [App\Http\Controllers\RojmelController::class, 'dashboard'])->name('rojmel.dashboard');
    Route::get('rojmel', [App\Http\Controllers\RojmelController::class, 'index'])->name('rojmel.index');
    Route::post('rojmel', [App\Http\Controllers\RojmelController::class, 'store'])->name('rojmel.store');
    Route::delete('rojmel/{rojmel}', [App\Http\Controllers\RojmelController::class, 'destroy'])->name('rojmel.destroy');
    Route::get('rojmel/report', [App\Http\Controllers\RojmelController::class, 'report'])->name('rojmel.report');
    Route::get('rojmel/export/pdf', [App\Http\Controllers\RojmelController::class, 'exportPdf'])->name('rojmel.pdf');
    Route::get('rojmel/export/excel', [App\Http\Controllers\RojmelController::class, 'exportExcel'])->name('rojmel.excel');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Google Sync Routes
    Route::prefix('settings/google-sync')->name('google.sync.')->group(function () {
        Route::get('/', [App\Http\Controllers\GoogleSyncController::class, 'settings'])->name('settings');
        Route::get('/redirect', [App\Http\Controllers\GoogleSyncController::class, 'redirect'])->name('redirect');
        Route::get('/callback', [App\Http\Controllers\GoogleSyncController::class, 'callback'])->name('callback');
        Route::post('/disconnect', [App\Http\Controllers\GoogleSyncController::class, 'disconnect'])->name('disconnect');
        Route::post('/toggle-auto', [App\Http\Controllers\GoogleSyncController::class, 'toggleAutoSync'])->name('toggle_auto');
        Route::post('/manual', [App\Http\Controllers\GoogleSyncController::class, 'manualSync'])->name('manual');
        Route::post('/manual/rojmel', [App\Http\Controllers\GoogleSyncController::class, 'manualSyncRojmel'])->name('manual.rojmel');
        Route::post('/manual/labour', [App\Http\Controllers\GoogleSyncController::class, 'manualSyncLabour'])->name('manual.labour');
    });

    // Plantation Plans
    Route::resource('plantation-plans', App\Http\Controllers\PlantationPlanController::class)->except(['create', 'edit', 'update']);
    Route::post('plantation-plans/preview', [App\Http\Controllers\PlantationPlanController::class, 'calculatePreview'])->name('plantation-plans.preview');
    Route::get('plantation-plans/{plantation_plan}/pdf', [App\Http\Controllers\PlantationPlanController::class, 'exportPdf'])->name('plantation-plans.pdf');
});

require __DIR__.'/auth.php';
