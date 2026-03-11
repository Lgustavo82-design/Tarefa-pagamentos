<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::post('/compra', [PaymentController::class, 'purchase']);

Route::post('/login', function() {
    return response()->json(['token' => 'fec9bb078bf338f464f96b48089eb498']);
});
