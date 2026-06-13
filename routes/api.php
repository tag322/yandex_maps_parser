<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\YandexPlaceController;

Route::get('/getPlace', [YandexPlaceController::class, 'getPlaceData']);
Route::post('/setPlace', [YandexPlaceController::class, 'setPlace']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});