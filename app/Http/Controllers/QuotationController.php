<?php
namespace App\Http\Controllers;
use App\Models\Quotation; use App\Models\QuotationItem; use App\Models\Product; use App\Models\Client;
use Illuminate\Http\Request; use Illuminate\Support\Facades\DB;
class QuotationController extends Controller {
    public function index() { $quotations = Quotation::all(); return view('quotations.index', compact('quotations')); }
    public function create() { $clients = Client::all(); $products = Product::all(); return view('quotations.create', compact('clients', 'products')); }
    public function store(Request $request) {
        $request->validate(['client_id' => 'required|exists:clients,id', 'valid_until' => 'nullable|date', 'items' => 'required|array|min:1', 'items.*.product_id' => 'required|exists:products,id', 'items.*.quantity' => 'required|integer|min:1', 'items.*.description' => 'nullable|string']);
        DB::transaction(function () use ($request) {
            $lastQuotation = Quotation::orderBy('id', 'desc')->first(); $nextId = $lastQuotation ? $lastQuotation->id + 1 : 1; $quotationNumber = 'QUO-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            $quotation = Quotation::create(['quotation_number' => $quotationNumber, 'client_id' => $request->client_id, 'valid_until' => $request->valid_until, 'status' => 'draft', 'subtotal' => 0, 'tax_amount' => 0, 'grand_total' => 0]);
            $subtotal = 0; $taxAmount = 0;
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $lineTotal = $product->unit_price * $item['quantity']; $itemTax = $lineTotal * ($product->tax_rate / 100);
                $subtotal += $lineTotal; $taxAmount += $itemTax;
                QuotationItem::create(['quotation_id' => $quotation->id, 'product_id' => $product->id, 'description' => $item['description'] ?? null, 'quantity' => $item['quantity'], 'unit_price' => $product->unit_price, 'line_total' => $lineTotal]);
            }
            $quotation->update(['subtotal' => $subtotal, 'tax_amount' => $taxAmount, 'grand_total' => $subtotal + $taxAmount]);
        });
        return redirect()->route('quotations.index')->with('success', 'Quotation created successfully.');
    }
}