<?php

use App\Actions\Pomodoro\Sessions\CreateSession;
use App\Actions\Pomodoro\Sessions\GetUserCurrentSession;
use App\Actions\Pomodoro\Sessions\GetUserSessions;
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

    Route::group([
        'prefix' => 'sessions'
    ], function () {
        Route::post('/', CreateSession::class);
        Route::get('/', GetUserSessions::class);

        Route::group([
            'prefix' => 'current'
        ], function () {
            Route::get('/', GetUserCurrentSession::class);
        });
    });
});
