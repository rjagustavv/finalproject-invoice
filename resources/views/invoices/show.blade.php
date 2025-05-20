<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invoice Detail: ') }} {{ $invoice->invoice_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold">Invoice Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                            <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
                            <p><strong>Customer Name:</strong> {{ $invoice->customer_name }}</p>
                            <p><strong>Delivery Date:</strong> {{ $invoice->delivery_date->format('d M Y') }}</p>
                            <p><strong>Submit Date:</strong> {{ $invoice->submit_date->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold mt-6 mb-2">Invoice Items</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Coil #</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Width</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Length</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Thickness</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Weight</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($invoice->details as $detail)
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap text-sm">{{ $detail->coil_number }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm">{{ number_format($detail->width, 2) }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm">{{ number_format($detail->length, 2) }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm">{{ number_format($detail->thickness, 2) }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm">{{ number_format($detail->weight, 2) }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-right">{{ number_format($detail->price, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-right font-semibold text-sm">Total Price:</td>
                                <td class="px-4 py-2 text-right font-bold text-sm">{{ number_format($invoice->total_price, 2, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('invoices.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>