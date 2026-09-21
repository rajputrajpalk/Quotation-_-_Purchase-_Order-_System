<!DOCTYPE html>
<html>
<head>
    <title>Create Quotation</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 bg-white shadow-xl rounded-xl">
        <div class="mb-6 border-b pb-4">
            <h2 class="text-3xl font-extrabold text-gray-900">Create New Quotation</h2>
        </div>
        
        <form action="{{ route('quotations.store') }}" method="POST" x-data="quotationForm()">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Client</label>
                    <select name="client_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">-- Choose a Client --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Valid Until</label>
                    <input type="date" name="valid_until" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Line Items</h3>
                <table class="min-w-full divide-y divide-gray-200 bg-white shadow-sm rounded-lg overflow-hidden">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left">Product</th>
                            <th class="px-4 py-3 text-left">Description</th>
                            <th class="px-4 py-3 text-left">Qty</th>
                            <th class="px-4 py-3 text-left">Unit Price</th>
                            <th class="px-4 py-3 text-left">Total</th>
                            <th class="px-4 py-3 text-left"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in items" :key="index">
                            <tr>
                                <td class="px-4 py-2">
                                    <select x-model="item.product_id" :name="`items[${index}][product_id]`" @change="updatePrice(index)" class="block w-full border-gray-300 rounded" required>
                                        <option value="">Select...</option>
                                        <template x-for="product in products" :key="product.id">
                                            <option :value="product.id" x-text="product.name"></option>
                                        </template>
                                    </select>
                                </td>
                                <td class="px-4 py-2"><input type="text" x-model="item.description" :name="`items[${index}][description]`" class="block w-full border-gray-300 rounded"></td>
                                <td class="px-4 py-2"><input type="number" x-model.number="item.quantity" :name="`items[${index}][quantity]`" @input="calculateTotals" class="block w-20 border-gray-300 rounded" min="1" required></td>
                                <td class="px-4 py-2 text-gray-700">$<span x-text="item.unit_price.toFixed(2)"></span></td>
                                <td class="px-4 py-2 font-medium">$<span x-text="(item.quantity * item.unit_price).toFixed(2)"></span></td>
                                <td class="px-4 py-2"><button type="button" @click="removeItem(index)" class="text-red-500">&times; Remove</button></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <div class="mt-4">
                    <button type="button" @click="addItem" class="px-4 py-2 bg-blue-600 text-white rounded">+ Add Item</button>
                </div>
            </div>

            <div class="flex justify-end mb-8">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 w-80">
                    <div class="flex justify-between mb-2"><span>Subtotal:</span><span>$<span x-text="subtotal.toFixed(2)"></span></span></div>
                    <div class="flex justify-between mb-2 border-b pb-2"><span>Tax:</span><span>$<span x-text="taxTotal.toFixed(2)"></span></span></div>
                    <div class="flex justify-between text-xl font-bold"><span>Grand Total:</span><span>$<span x-text="grandTotal.toFixed(2)"></span></span></div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-bold shadow-md">Save Quotation</button>
            </div>
        </form>
    </div>
    <script>
        const availableProducts = @json($products);
        function quotationForm() {
            return {
                products: availableProducts,
                items: [{ product_id: '', description: '', quantity: 1, unit_price: 0, tax_rate: 0 }],
                subtotal: 0, taxTotal: 0, grandTotal: 0,
                addItem() { this.items.push({ product_id: '', description: '', quantity: 1, unit_price: 0, tax_rate: 0 }); },
                removeItem(index) { this.items.splice(index, 1); this.calculateTotals(); },
                updatePrice(index) {
                    const product = this.products.find(p => p.id == this.items[index].product_id);
                    if (product) { this.items[index].unit_price = parseFloat(product.unit_price); this.items[index].tax_rate = parseFloat(product.tax_rate); } 
                    else { this.items[index].unit_price = 0; this.items[index].tax_rate = 0; }
                    this.calculateTotals();
                },
                calculateTotals() {
                    this.subtotal = 0; this.taxTotal = 0;
                    this.items.forEach(item => {
                        const lineTotal = item.quantity * item.unit_price;
                        this.subtotal += lineTotal; this.taxTotal += lineTotal * (item.tax_rate / 100);
                    });
                    this.grandTotal = this.subtotal + this.taxTotal;
                }
            }
        }
    </script>
</body>
</html>