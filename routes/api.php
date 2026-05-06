<?php

use Illuminate\Http\Request;
use App\Http\Controllers\XenditCallbackController;
use Illuminate\Support\Facades\Route;

Route::post('/xendit/callback', [XenditCallbackController::class, 'handle']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
