<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LoginController;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/', [LoginController::class, 'login'])->name('login.submit');
});

// Public temporary PDF share link for WhatsApp
Route::get('/share/invoice/{token}', [InvoiceController::class, 'sharedPdf'])->name('invoices.shared');

// Authenticated — admin & superadmin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [InvoiceController::class, 'index'])->name('dashboard');
    Route::get('/invoices/export-csv', [InvoiceController::class, 'exportCsv'])->name('invoices.export');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('create_invoice');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
    Route::post('/invoices/{invoice}/whatsapp', [InvoiceController::class, 'sendWhatsApp'])->name('invoices.whatsapp');
    Route::post('/invoices/{invoice}/email', [InvoiceController::class, 'sendEmail'])->name('invoices.email');
    Route::post('/invoices/{invoice}/payments', [InvoiceController::class, 'storePayment'])->name('invoices.payments.store');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Super Admin only — edit & delete
Route::middleware(['auth', 'superadmin'])->group(function () {
    Route::get('/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
});
