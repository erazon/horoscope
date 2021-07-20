<?php

use App\Http\Controllers\HoroscopeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// to generate the score for a year
Route::post('/generate/{year}', [HoroscopeController::class, 'generate']);

// zodiac calendar for a given year and zodiac sign id
Route::get('/calendar/{year}/{zodiac_sign_id}', [HoroscopeController::class, 'calendar']);

// best month for a given year and zodiac sign id
Route::get('/best-month/{year}/{zodiac_sign_id}', [HoroscopeController::class, 'bestMonth']);

// best zodiac sign for a given year
Route::get('/best-zodiac/{year}', [HoroscopeController::class, 'bestYear']);
