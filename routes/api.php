<?php

use App\Http\Controllers\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//
//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::post('/book-space', [BookingController::class, 'bookSpace']);
Route::post('/bulk-booking', [BookingController::class, 'bulkBooking']);
