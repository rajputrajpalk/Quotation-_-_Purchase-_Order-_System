<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\PurchaseOrderController;

Route::get('/', function () {
    return redirect()->route('quotations.index');
});

Route::resource('quotations', QuotationController::class);
Route::resource('purchase_orders', PurchaseOrderController::class);
Route::post('quotations/{quotation}/convert', [PurchaseOrderController::class, 'convertFromQuotation'])->name('purchase_orders.convert');