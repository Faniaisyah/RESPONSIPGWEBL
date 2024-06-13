<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PointController;
use App\Http\Controllers\PolylineController;
use App\Http\Controllers\PolygonController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//GeoJSON point
Route::get('/points', [PointController::class, 'index'])->name('points');
Route::get('/point/{id}', [PointController::class, 'show'])->name('point');

//GeoJSON polyline
Route::get('/polylines', [PolylineController::class, 'index'])->name('polylines');
Route::get('/polyline/{id}', [PolylineController::class, 'show'])->name('polyline');

//GeoJSON polygon
Route::get('/polygons', [PolygonController::class, 'index'])->name('polygons');
Route::get('/polygon/{id}', [PolygonController::class, 'show'])->name('polygon');
