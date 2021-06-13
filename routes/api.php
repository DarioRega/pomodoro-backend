<?php

use App\Actions\Pomodoro\Sessions\AbortSession;
use App\Actions\Pomodoro\Sessions\CreateSession;
use App\Actions\Pomodoro\Sessions\Getters\GetUserCurrentSession;
use App\Actions\Pomodoro\Sessions\Getters\GetUserSessions;
use App\Actions\Pomodoro\Sessions\StartSession;
use App\Actions\Pomodoro\Steps\Getters\GetUserCurrentSessionSteps;
use App\Actions\Pomodoro\Steps\Getters\GetUserCurrentStep;
use App\Actions\Pomodoro\Steps\UserActions\RunActionIntoCurrentStep;
use App\Actions\User\GetUser;
use App\Actions\User\PomodoroSettings\CreatePomodoroSettings;
use App\Actions\User\Settings\UpdateUserSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
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
Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::group([
    'prefix' => 'user',
    'middleware' => 'auth:sanctum'
], function () {
    Route::get('/', GetUser::class);
    Route::post('/settings', UpdateUserSettings::class);

    Route::group(['prefix' => 'pomodoro-settings'], function () {
        Route::post('/', CreatePomodoroSettings::class);
    });

    Route::group(['prefix' => 'sessions'], function () {
        Route::post('/', CreateSession::class);
        Route::get('/', GetUserSessions::class);

        Route::group(['prefix' => '{session}'], function () {
            Route::get('/start', StartSession::class);
        });

        Route::group(['prefix' => 'current'], function () {
            Route::get('/', GetUserCurrentSession::class);
            Route::get('/abort', AbortSession::class);

            Route::group(['prefix' => 'steps'], function () {
                Route::get('/', GetUserCurrentSessionSteps::class);

                Route::group(['prefix' => 'current'], function () {
                    Route::get('/', GetUserCurrentStep::class);
                    Route::post('/action', RunActionIntoCurrentStep::class);
                });
            });
        });
    });
});
