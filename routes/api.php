<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/vapi/webhook', [\App\Http\Controllers\Api\VapiWebhookController::class, 'handle']);
