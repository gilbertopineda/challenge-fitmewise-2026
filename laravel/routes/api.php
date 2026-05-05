<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use App\AccessControl\Application\Commands\CheckInUserCommand;
use App\AccessControl\Application\Handlers\CheckInUserHandler;

Route::post('/check-in', function (Request $request) {

    $command = new CheckInUserCommand(
        userId: $request->input('user_id')
    );

    app(CheckInUserHandler::class)->handle($command);

    return response()->json([
        'status' => 'ok'
    ]);
});

Route::get('/dashboard/{userId}', DashboardController::class);
