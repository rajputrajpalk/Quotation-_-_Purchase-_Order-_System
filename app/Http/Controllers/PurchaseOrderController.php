<?php
namespace App\Http\Controllers;
use App\Models\PurchaseOrder; use App\Models\PurchaseOrderItem; use App\Models\Quotation;
use Illuminate\Http\Request; use Illuminate\Support\Facades\DB;
class PurchaseOrderController extends Controller {
    public function index() { $purchaseOrders = PurchaseOrder::all(); return view('purchase_orders.index', compact('purchaseOrders')); }
    public function convertFromQuotation(Quotation $quotation) {
        if ($quotation->status === 'converted') return redirect()->back()->with('error', 'Quotation is already converted.');
        DB::transaction(function () use ($quotation) {
            $lastPo = PurchaseOrder::orderBy('id', 'desc')->first(); $nextId = $lastPo ? $lastPo->id + 1 : 1; $poNumber = 'PO-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            $purchaseOrder = PurchaseOrder::create(['po_number' => $poNumber, 'quotation_id' => $quotation->id, 'client_id' => $quotation->client_id, 'status' => 'pending', 'subtotal' => $quotation->subtotal, 'tax_amount' => $quotation->tax_amount, 'grand_total' => $quotation->grand_total, 'order_date' => now()]);
            foreach ($quotation->items as $item) { PurchaseOrderItem::create(['purchase_order_id' => $purchaseOrder->id, 'product_id' => $item->product_id, 'description' => $item->description, 'quantity' => $item->quantity, 'unit_price' => $item->unit_price, 'line_total' => $item->line_total]); }
            $quotation->update(['status' => 'converted']);
        });
        return redirect()->route('purchase_orders.index')->with('success', 'Converted to Purchase Order successfully.');
    }
}