<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/vapi/webhook', [\App\Http\Controllers\Api\VapiWebhookController::class, 'handle']);

Route::get('/vapi/assistance-types', function() {
    $types = \App\Models\Department::where('is_active', true)->pluck('name')->toArray();
    return response()->json(['available_types' => $types]);
});
