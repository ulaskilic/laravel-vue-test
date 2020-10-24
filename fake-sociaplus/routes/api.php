<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('leagues', \App\Http\Controllers\LeagueController::class);
Route::apiResource('leagues.teams', \App\Http\Controllers\TeamController::class);

Route::post('leagues/{league}/distribute-fixture', [\App\Http\Controllers\MatchController::class, 'distributeFixture']);
Route::post('leagues/{league}/play-one-week', [\App\Http\Controllers\MatchController::class, 'simulateOneWeek']);
Route::post('leagues/{league}/play-all', [\App\Http\Controllers\MatchController::class, 'simulateAll']);
Route::get('debug', [\App\Http\Controllers\MatchController::class, 'debug']);
