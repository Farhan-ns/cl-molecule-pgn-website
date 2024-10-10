<?php

use App\Http\Controllers\Api\CompaniesController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ScanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/scan', [ScanController::class, 'parseScan']);
    Route::post('/second-scan', [ScanController::class, 'parseSecondScan']);
});

Route::get('/companies-domicile', [CompaniesController::class, 'getCompanies'])->name('api.getCompanies');

Route::get('/{id}/notifications/', [NotificationController::class, 'getUnreadNotifications']);
Route::post('/login', [LoginController::class, 'auth']);