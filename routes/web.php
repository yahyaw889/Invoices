<?php

use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SectionController;
use App\Models\Attachment_invoice;
use Illuminate\Support\Facades\Route;



Route::middleware('auth')->group(function(){

    Route::get('invoices/{id}/filter', [InvoicesController::class , 'filter'])->name('invoices.filter');
    Route::get('invoices/{invoices}/print' , [InvoicesController::class , 'print'])->name('invoices.print');
    Route::get('invoices/archive' , [InvoicesController::class , 'archive'])->name('invoices.archive');
    Route::get('invoices/{id}/archive' , [InvoicesController::class , 'archiveFilter']);
    Route::get('invoices/{id}/status' , [InvoicesController::class , 'status'])->name('invoices.status');
    Route::get('invoices/{id}/restore' , [InvoicesController::class , 'restore'])->name('invoices.restore');
    Route::delete('invoices/delete/{id}' , [InvoicesController::class , 'delete'])->name('invoices.delete');
    Route::post('invoices/status/{id}', [InvoicesController::class, 'updateStatus'])->name('status.update');
    Route::resource('/invoices', InvoicesController::class)->names([
        'index'   => 'invoices.index',
        'create'  => 'invoices.create',
        'store'   => 'invoices.store',
        'show'    => 'invoices.show',
        'edit'    => 'invoices.edit',
        'update'  => 'invoices.update',
        'destroy' => 'invoices.destroy',
    ]);

    Route::resource('section' , SectionController::class);
    Route::resource('product' , ProductController::class);
    Route::get('section/{section}' , [InvoicesController::class , 'getProducts']);
    Route::get('attachment/img/{fileName}',[\App\Http\Controllers\AttachmentInvoiceController::class, 'index'])->name('attachment.index');
    Route::post('attachment',[\App\Http\Controllers\AttachmentInvoiceController::class, 'store'])->name('attachment.store');
    Route::delete('attachment/{attachment_invoice}',[\App\Http\Controllers\AttachmentInvoiceController::class, 'destroy'])->name('attachment.destroy');


    Route::get('/', function () {return view('sweet-alert');});
    Route::get('/k', function () {return view('invoice');});
});






Route::get('/index', function () {
    return view('index');
})->middleware(['auth', 'verified'])->name('dashboard');
//
//Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//});

require __DIR__.'/auth.php';
