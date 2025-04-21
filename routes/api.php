<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DistanceController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('distance')->group(function() {
    Route::get('/haversine-package', [DistanceController::class, 'haversinePackage']);
    Route::get('/spherical-law', [DistanceController::class, 'sphericalLaw']);
    Route::get('/mysql', [DistanceController::class, 'mysqlDistance']);
    Route::get('/geo-package', [DistanceController::class, 'geoPackage']);
});
