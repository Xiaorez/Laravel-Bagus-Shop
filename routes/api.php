<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Api\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', function (\Illuminate\Http\Request $request) {
        return response()->json([
            'user' => $request->user(),
        ]);
    });

    Route::post('/logout', function (\Illuminate\Http\Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout success',
        ]);
    });

    Route::apiResource('products', ProductController::class);
});
