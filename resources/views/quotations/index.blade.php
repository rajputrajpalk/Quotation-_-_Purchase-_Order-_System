<!DOCTYPE html>
<html>
<head>
    <title>Quotations List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 bg-white shadow-xl rounded-xl">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h2 class="text-3xl font-extrabold text-gray-900">Quotations</h2>
            <a href="{{ route('quotations.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Create New</a>
        </div>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left">Quote #</th>
                    <th class="px-4 py-2 text-left">Client</th>
                    <th class="px-4 py-2 text-left">Total</th>
                    <th class="px-4 py-2 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quotations as $quote)
                <tr>
                    <td class="px-4 py-2">{{ $quote->quotation_number }}</td>
                    <td class="px-4 py-2">{{ optional($quote->client)->name }}</td>
                    <td class="px-4 py-2">${{ number_format($quote->grand_total, 2) }}</td>
                    <td class="px-4 py-2 font-bold">{{ strtoupper($quote->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>