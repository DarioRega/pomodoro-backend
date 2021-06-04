<?php

use App\Actions\Pomodoro\Sessions\CreateSession;
use App\Actions\Pomodoro\Sessions\Getters\GetUserCurrentSession;
use App\Actions\Pomodoro\Sessions\Getters\GetUserSessions;
use App\Actions\Pomodoro\Steps\Getters\GetUserCurrentSessionSteps;
use App\Actions\Pomodoro\Steps\Getters\GetUserCurrentStep;
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

Route::group([
    'prefix' => 'user',
    'middleware' => 'auth:sanctum'
], function () {
    Route::get('/', function (Request $request) {
        return $request->user();
    });

    Route::group(['prefix' => 'sessions'], function () {
        Route::post('/', CreateSession::class);
        Route::get('/', GetUserSessions::class);

        Route::group(['prefix' => 'current'], function () {
            Route::get('/', GetUserCurrentSession::class);

            Route::group(['prefix' => 'steps'], function () {
                Route::get('/', GetUserCurrentSessionSteps::class);

                Route::group(['prefix' => 'current'], function () {
                    Route::get('/', GetUserCurrentStep::class);
                });
            });
        });
    });
});
