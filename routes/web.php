<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\GeoServerController;
use App\Http\Controllers\GeoServerController2;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\PolygonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PolylineController;
use App\Http\Controllers\WFSController;
use App\Models\Points;
use App\Models\Polylines;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [MapController::class, 'index'])->name('index');
Route::get('/table', [MapController::class, 'table'])->name('table');


//create point
Route::post('/store-point', [PointController::class, 'store'])->name('store-point');
Route::delete('delete-point/{id}', [PointController::class, 'destroy'])->name('delete-point');
//edit point
Route::get('/edit-point/{id}', [PointController::class, 'edit'])->name('edit-point');

//update point
Route::patch('/update-point/{id}', [PointController::class, 'update'])->name('update-point');

//update point
Route::patch('/update-polyline/{id}', [PolylineController::class, 'update'])->name('update-polyline');

//update point
Route::patch('/update-polygon/{id}', [PolygonController::class, 'update'])->name('update-polygon');


//create polyline
Route::post('/store-polyline', [PolylineController::class, 'store'])->name('store-polyline');
Route::delete('delete-polyline/{id}', [PolylineController::class, 'destroy'])->name('delete-polyline');
//edit point
Route::get('/edit-polyline/{id}', [PolylineController::class, 'edit'])->name('edit-polyline');

//create polygon
Route::post('/store-polygon', [PolygonController::class, 'store'])->name('store-polygon');
Route::delete('delete-polygon/{id}', [PolygonController::class, 'destroy'])->name('delete-polygon');
//edit point
Route::get('/edit-polygon/{id}', [PolygonController::class, 'edit'])->name('edit-polygon');

Route::get('/about', function () {
    return view('about');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//table
Route::get('/table-point', [PointController::class, 'table'])->name('table-point');

//table
Route::get('/table-polyline', [PolylineController::class, 'table'])->name('table-polyline');

//table
Route::get('/table-polygon', [PolygonController::class, 'table'])->name('table-polygon');

Route::get('/geoserver-data', [GeoServerController::class, 'getData'])->name('geoserver.data');

Route::get('/geoserver-data', [GeoServerController::class, 'getData']);

Route::get('/geoserver', [GeoServerController::class, 'show']);

Route::get('/output-point', [PointController::class, 'outputPoint'])->name('output-point');

Route::get('/output-polygon', [PolygonController::class, 'outputPolygon'])->name('output-polygon');

Route::get('/output-polyline', [PolylineController::class, 'outputPolyline'])->name('output-polyline');





use App\Http\Controllers\YourController;


require __DIR__ . '/auth.php';

