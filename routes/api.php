<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\YellowPageController;
use Illuminate\Support\Facades\Route;

Route::get('yellow-pages', [YellowPageController::class, 'index']);
Route::get('yellow-pages/categories', [YellowPageController::class, 'categories']);
Route::get('yellow-pages/{yellowPage}', [YellowPageController::class, 'show']);
Route::post('yellow-pages/{yellowPage}/apply', [ApplicationController::class, 'store']);
Route::get('applications/{application}', [ApplicationController::class, 'show']);
Route::post('applications/{application}/payment', [PaymentController::class, 'initiate']);
Route::get('applications/{application}/payment-status', [PaymentController::class, 'status']);
Route::post('payment/callback', [PaymentController::class, 'callback']);
Route::get('applications/{application}/receipt', [ReceiptController::class, 'show']);
Route::get('receipts/{reference}/verify', [ReceiptController::class, 'verify']);
