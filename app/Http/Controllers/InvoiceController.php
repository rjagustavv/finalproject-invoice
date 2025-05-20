<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetail; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // Untuk validasi unik saat update

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::orderBy('submit_date', 'desc')->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $year = date('y');
        $prefix = "JSGI-INV-{$year}-";
        $lastInvoice = Invoice::where('invoice_number', 'LIKE', $prefix . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();
        $nextNumber = 1;
        if ($lastInvoice) {
            $lastNumStr = substr($lastInvoice->invoice_number, strlen($prefix));
            $nextNumber = intval($lastNumStr) + 1;
        }
        $invoiceNumber = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return view('invoices.create', compact('invoiceNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|string|max:50|unique:invoices,invoice_number',
            'customer_name' => 'required|string|max:100',
            'delivery_date' => 'required|date',
            'details' => 'required|array|min:1',
            'details.*.coil_number' => 'required|string|max:50',
            'details.*.width' => 'required|numeric|min:0',
            'details.*.length' => 'required|numeric|min:0',
            'details.*.thickness' => 'required|numeric|min:0',
            'details.*.weight' => 'required|numeric|min:0',
            'details.*.price' => 'required|numeric|min:0',
        ]);

                DB::beginTransaction();
        try {
            $totalAmount = 0;
            foreach ($request->details as $detail) {
                $totalAmount += (float)$detail['price'];
            }

            $invoice = Invoice::create([
                'invoice_number' => $request->invoice_number,
                'customer_name' => $request->customer_name,
                'delivery_date' => $request->delivery_date,
                'submit_date' => Carbon::now(),
                'total_amount' => $totalAmount, 
                'user_id' => Auth::id(), // Tambahkan ini untuk mengambil ID user yang login
            ]);

            foreach ($request->details as $detailData) {
                $invoice->details()->create($detailData);
            }

            DB::commit();
            return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error creating invoice: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load('details');
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        $invoice->load('details'); // Muat detail untuk form edit
        return view('invoices.edit', compact('invoice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            // Invoice number tidak boleh diubah, atau jika boleh, validasinya harus disesuaikan
            // 'invoice_number' => ['required','string','max:50', Rule::unique('invoices')->ignore($invoice->id)],
            'customer_name' => 'required|string|max:100',
            'delivery_date' => 'required|date',
            'details' => 'required|array|min:1',
            'details.*.coil_number' => 'required|string|max:50',
            'details.*.width' => 'required|numeric|min:0',
            'details.*.length' => 'required|numeric|min:0',
            'details.*.thickness' => 'required|numeric|min:0',
            'details.*.weight' => 'required|numeric|min:0',
            'details.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            foreach ($request->details as $detail) {
                $totalAmount += (float)$detail['price'];
            }

            $invoice->update([
                'customer_name' => $request->customer_name,
                'delivery_date' => $request->delivery_date,
                // 'submit_date' => Carbon::now(), // Biasanya submit_date tidak diubah saat edit, atau sesuai kebutuhan
                'total_amount' => $totalAmount,
            ]);

            // Hapus detail lama
            $invoice->details()->delete();

            // Tambahkan detail baru/yang diperbarui
            foreach ($request->details as $detailData) {
                // Tidak perlu mengirim 'invoice_id' karena relasi sudah menanganinya
                $invoice->details()->create($detailData);
            }

            DB::commit();
            return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error updating invoice: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        DB::beginTransaction();
        try {
            // Detail akan terhapus otomatis karena onDelete('cascade') di migrasi
            $invoice->delete();
            DB::commit();
            return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('invoices.index')->with('error', 'Error deleting invoice: ' . $e->getMessage());
        }
    }

    public function print(Invoice $invoice)
    {
        $invoice->load('details'); // Pastikan detail invoice juga dimuat
        return view('invoices.print', compact('invoice'));
    }
}
