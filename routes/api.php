<?php

use App\Http\Controllers\API\AuthController as AuthControllerAPI;
use App\Http\Controllers\API\FormGuestController as FormGuestAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthControllerAPI::class, 'login'])->name('login');
Route::post('logout', [AuthControllerAPI::class, 'logout'])->middleware('auth:sanctum');

Route::prefix('form-guest')->group(function() {
    Route::post('/', [FormGuestAPI::class, 'store']);
    Route::get('gallery', [FormGuestAPI::class, 'gallery']);

});

Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::get('form-guest', [FormGuestAPI::class, 'index']);
    Route::delete('form-guest/delete/{id}', [FormGuestAPI::class, 'destroy']);
});
