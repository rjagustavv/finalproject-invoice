<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Invoice: ') }} {{ $invoice->invoice_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                     @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('invoices.update', $invoice->id) }}">
                        @csrf
                        @method('PUT') {{-- Metode untuk update --}}

                        <!-- Invoice Master -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="invoice_number" class="block text-sm font-medium text-gray-700">Invoice No</label>
                                <input type="text" name="invoice_number_display" id="invoice_number_display" value="{{ $invoice->invoice_number }}" readonly class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-gray-100 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                {{-- <input type="hidden" name="invoice_number" value="{{ $invoice->invoice_number }}"> --}}
                                <small class="text-xs text-gray-500">Invoice number cannot be changed.</small>
                            </div>
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700">Customer Name</label>
                                <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', $invoice->customer_name) }}" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="input name customer">
                            </div>
                            <div>
                                <label for="delivery_date" class="block text-sm font-medium text-gray-700">Delivery Date</label>
                                <input type="date" name="delivery_date" id="delivery_date" value="{{ old('delivery_date', $invoice->delivery_date->format('Y-m-d')) }}" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="input delivery date">
                            </div>
                        </div>

                        <!-- Invoice Details -->
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-2">Detail Invoice</h3>
                        <div id="invoice-details-container" class="space-y-4">
                            {{-- Loop untuk detail yang sudah ada --}}
                            @foreach (old('details', $invoice->details->toArray()) as $index => $detail)
                            <div class="detail-item-active p-4 border border-gray-200 rounded-md">
                                <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Coil Number</label>
                                        <input type="text" name="details[{{$index}}][coil_number]" value="{{ $detail['coil_number'] ?? '' }}" class="detail-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm sm:text-sm" placeholder="Coil" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Width</label>
                                        <input type="number" step="0.01" name="details[{{$index}}][width]" value="{{ $detail['width'] ?? '' }}" class="detail-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm sm:text-sm" placeholder="Width" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Length</label>
                                        <input type="number" step="0.01" name="details[{{$index}}][length]" value="{{ $detail['length'] ?? '' }}" class="detail-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm sm:text-sm" placeholder="Length" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Thickness</label>
                                        <input type="number" step="0.01" name="details[{{$index}}][thickness]" value="{{ $detail['thickness'] ?? '' }}" class="detail-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm sm:text-sm" placeholder="Thick" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Weight</label>
                                        <input type="number" step="0.01" name="details[{{$index}}][weight]" value="{{ $detail['weight'] ?? '' }}" class="detail-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm sm:text-sm" placeholder="Weight" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Price</label>
                                        <input type="number" step="0.01" name="details[{{$index}}][price]" value="{{ $detail['price'] ?? '' }}" class="detail-input detail-price mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm sm:text-sm" placeholder="Price" required>
                                    </div>
                                </div>
                                <button type="button" class="remove-detail-item mt-2 text-red-500 hover:text-red-700 text-sm">Remove Line</button>
                            </div>
                            @endforeach
                        </div>

                        <!-- Template untuk item baru (hidden) -->
                        <div class="detail-item-template hidden p-4 border border-gray-200 rounded-md">
                            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Coil Number</label>
                                    <input type="text" data-name="coil_number" class="detail-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm sm:text-sm" placeholder="Coil">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Width</label>
                                    <input type="number" step="0.01" data-name="width" class="detail-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm sm:text-sm" placeholder="Width">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Length</label>
                                    <input type="number" step="0.01" data-name="length" class="detail-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm sm:text-sm" placeholder="Length">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Thickness</label>
                                    <input type="number" step="0.01" data-name="thickness" class="detail-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm sm:text-sm" placeholder="Thick">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Weight</label>
                                    <input type="number" step="0.01" data-name="weight" class="detail-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm sm:text-sm" placeholder="Weight">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Price</label>
                                    <input type="number" step="0.01" data-name="price" class="detail-input detail-price mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm sm:text-sm" placeholder="Price">
                                </div>
                            </div>
                            <button type="button" class="remove-detail-item mt-2 text-red-500 hover:text-red-700 text-sm">Remove Line</button>
                        </div>
                        <!-- End of template -->

                        <button type="button" id="add-detail-item" class="mt-4 mb-6 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Add Line
                        </button>
                        <small class="block text-xs text-gray-500 mb-6">Untuk menambahkan baris baru pada detail invoice</small>

                        <div class="flex justify-end items-center mt-6 mb-4">
                            <span class="text-lg font-semibold text-gray-700 mr-2">Total Price:</span>
                            <span id="total-price-display" class="text-xl font-bold text-gray-900">0.00</span>
                        </div>
                        <small class="block text-xs text-gray-500 mb-6 text-right">Kalkulasi otomatis ketika harga pada detail invoice diinputkan</small>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('invoices.index') }}" class="mr-2 inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Update Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('invoice-details-container');
            const addButton = document.getElementById('add-detail-item');
            const template = document.querySelector('.detail-item-template');
            // Hitung index awal berdasarkan item yang sudah ada dari backend (termasuk dari old input)
            let detailIndex = container.querySelectorAll('.detail-item-active').length;

            function addDetailItem(existingData = null) {
                const newItem = template.cloneNode(true);
                newItem.classList.remove('hidden', 'detail-item-template');
                newItem.classList.add('detail-item-active');

                newItem.querySelectorAll('.detail-input').forEach(input => {
                    const nameBase = input.dataset.name;
                    input.name = `details[${detailIndex}][${nameBase}]`;
                    input.required = true;
                    if (existingData && existingData[nameBase] !== undefined) {
                        input.value = existingData[nameBase];
                    }
                    if (input.classList.contains('detail-price')) {
                        input.addEventListener('input', calculateTotalPrice);
                    }
                });

                newItem.querySelector('.remove-detail-item').addEventListener('click', function () {
                    newItem.remove();
                    calculateTotalPrice();
                    // Re-index after remove? Not strictly necessary if backend handles array keys correctly.
                });

                container.appendChild(newItem);
                detailIndex++;
                if (!existingData) { // Hanya hitung total jika item baru, bukan item yang dimuat
                    calculateTotalPrice();
                }
            }

            function calculateTotalPrice() {
                let total = 0;
                document.querySelectorAll('.detail-item-active .detail-price').forEach(priceInput => {
                    const value = parseFloat(priceInput.value);
                    if (!isNaN(value)) {
                        total += value;
                    }
                });
                document.getElementById('total-price-display').textContent = total.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            addButton.addEventListener('click', () => addDetailItem());

            // Inisialisasi event listener untuk item yang sudah ada (jika ada dari old() atau $invoice->details)
            document.querySelectorAll('.detail-item-active .detail-price').forEach(priceInput => {
                priceInput.addEventListener('input', calculateTotalPrice);
            });
            document.querySelectorAll('.detail-item-active .remove-detail-item').forEach(button => {
                button.addEventListener('click', function () {
                    this.closest('.detail-item-active').remove();
                    calculateTotalPrice();
                });
            });


            // Panggil addDetailItem jika old('details') kosong dan tidak ada detail dari $invoice (form baru)
            // atau jika tidak ada old('details') dan tidak ada $invoice->details (ini seharusnya tidak terjadi di edit)
            // Pada edit, data detail sudah dirender oleh Blade loop.
            // Jika tidak ada old('details') dan $invoice->details kosong (invoice baru tanpa detail), maka kita bisa tambahkan satu.
            // Namun, logika ini lebih cocok untuk create.blade.php. Di edit, kita asumsikan detail sudah ada.

            // Initial calculation
            calculateTotalPrice();
        });
    </script>
    @endpush
</x-app-layout>