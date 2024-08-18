<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('payment', [PaymentController::class, 'createPayment']);
Route::get('payment/attach', [PaymentController::class, 'attachPayment']);
Route::get('payment/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
Route::get('payment/failed', [PaymentController::class, 'paymentFailed'])->name('payment.failed');

