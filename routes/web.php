<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvoiceController; // Pastikan ini ada
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    // return view('dashboard');
    return redirect()->route('invoices.index'); // Arahkan dashboard ke invoice index
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
    Route::resource('invoices', InvoiceController::class);
});

require __DIR__.'/auth.php';