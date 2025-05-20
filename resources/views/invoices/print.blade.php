<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            background-color: #f9f9f9; /* Warna background agar terlihat beda dari area cetak putih */
        }
        .invoice-container {
            width: 800px; /* Lebar tetap untuk preview, atau bisa 100% jika ingin responsif */
            margin: 20px auto; /* Tengah */
            padding: 30px;
            background-color: #fff; /* Area konten invoice putih */
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #000;
        }
        .company-details, .invoice-details, .customer-details {
            margin-bottom: 20px;
        }
        .company-details p, .invoice-details p, .customer-details p {
            margin: 5px 0;
            font-size: 14px;
        }
        .company-details strong, .invoice-details strong, .customer-details strong {
            display: inline-block;
            width: 120px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            font-size: 14px;
        }
        th {
            background-color: #f0f0f0; /* Sedikit lebih gelap untuk header tabel */
        }
        .text-right {
            text-align: right;
        }
        .total-section {
            margin-top: 20px;
            text-align: right;
        }
        .total-section p {
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0;
        }
        .print-actions { /* Kontainer untuk tombol */
            text-align: center;
            margin-top: 30px;
            padding-bottom: 20px; /* Beri sedikit ruang di bawah tombol */
        }
        .print-actions button {
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #007bff; /* Warna biru untuk tombol cetak */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 5px;
        }
        .print-actions button:hover {
            background-color: #0056b3;
        }
        .print-actions .close-button {
             background-color: #6c757d; /* Warna abu-abu untuk tombol close */
        }
        .print-actions .close-button:hover {
             background-color: #545b62;
        }


        /* Aturan khusus untuk media cetak */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background-color: #fff; /* Pastikan background putih saat cetak */
                margin: 0;
                padding: 0;
            }
            .invoice-container {
                width: 100%;
                margin: 0;
                padding: 20px; /* Sesuaikan padding untuk cetak jika perlu */
                border: none;
                box-shadow: none;
            }
            .print-actions { /* Sembunyikan tombol saat mencetak */
                display: none !important;
            }
        }
    </style>
</head>
<body>
    {{-- Tombol Aksi (Cetak dan Tutup) akan diletakkan di atas atau bawah --}}
    <div class="print-actions">
        <button onclick="window.print()">Cetak Invoice</button>
        <button onclick="window.close()" class="close-button">Tutup Halaman</button>
    </div>

    <div class="invoice-container">
        <div class="header">
            <h1>INVOICE</h1>
            {{-- <img src="/path/to/your/logo.png" alt="Company Logo" style="max-width: 150px; margin-bottom: 10px;"> --}}
        </div>

        <table style="width:100%; border:0px; margin-bottom: 20px;">
            <tr style="border:0px;">
                <td style="width:50%; vertical-align: top; border:0px; padding:0px;">
                    <div class="company-details">
                        <p><strong>From:</strong></p>
                        <p>HilockSandy Corp.</p>
                        <p>Jl. Industri Jaya No. 123</p>
                        <p>Kota Makmur, 12345</p>
                        <p>Telp: (021) 555-1234</p>
                    </div>
                </td>
                <td style="width:50%; vertical-align: top; border:0px; padding:0px;">
                    <div class="invoice-details" style="text-align: right;">
                        <p><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</p>
                        <p><strong>Date:</strong> {{ $invoice->submit_date ? $invoice->submit_date->format('d M Y') : 'N/A' }}</p>
                        <p><strong>Delivery Date:</strong> {{ $invoice->delivery_date->format('d M Y') }}</p>
                    </div>
                </td>
            </tr>
             <tr style="border:0px;">
                <td style="width:50%; vertical-align: top; border:0px; padding:0px; padding-top:15px;">
                    <div class="customer-details">
                        <p><strong>To:</strong></p>
                        <p>{{ $invoice->customer_name }}</p>
                        {{-- <p>Alamat Customer</p> --}}
                        {{-- <p>Kota Customer</p> --}}
                    </div>
                </td>
                <td style="width:50%; vertical-align: top; border:0px; padding:0px;">
                    {{-- Kosongkan --}}
                </td>
            </tr>
        </table>


        <table>
            <thead>
                <tr>
                    <th>Coil Number</th>
                    <th>Width</th>
                    <th>Length</th>
                    <th>Thickness</th>
                    <th>Weight</th>
                    <th class="text-right">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->details as $detail)
                <tr>
                    <td>{{ $detail->coil_number }}</td>
                    <td>{{ number_format($detail->width, 2) }}</td>
                    <td>{{ number_format($detail->length, 2) }}</td>
                    <td>{{ number_format($detail->thickness, 2) }}</td>
                    <td>{{ number_format($detail->weight, 2) }}</td>
                    <td class="text-right">{{ number_format($detail->price, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <p>Total: Rp {{ number_format($invoice->total_price ?? $invoice->total_amount ?? 0, 2, ',', '.') }}</p>
        </div>

       
    </div>

</body>
</html>