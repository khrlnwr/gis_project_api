<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventTypeController;
use App\Http\Controllers\Api\CitiesAreaController;
use App\Http\Controllers\Api\PronesAreaController;
use App\Http\Controllers\Api\ProvincesAreaController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('prones_area', [PronesAreaController::class, 'index']);
Route::post('prones_area', [PronesAreaController::class, 'insert']);
Route::post('prones_area/filter', [PronesAreaController::class, 'filterPronesArea']);

Route::get('event_type', [EventTypeController::class, 'index']);
Route::post('event_type', [EventTypeController::class, 'insert']);
Route::get('event_type/{id}', [EventTypeController::class, 'show']);

Route::get('provinces', [ProvincesAreaController::class, 'index']);
Route::post('provinces', [ProvincesAreaController::class, 'insert']);
Route::post('provinces/import_csv', [ProvincesAreaController::class, 'importCSV']);

Route::get('cities', [CitiesAreaController::class, 'index']);
Route::post('create_city', [CitiesAreaController::class, 'insert']);
Route::get('select_cities_by_province/{id}', [CitiesAreaController::class, 'showCitiesByProvinceId']);

