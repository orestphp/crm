<?php

use App\Http\Controllers\TicketApiController;
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

Route::middleware('auth')->get('/user', function (Request $request) {
    return response()->json([
        'id' => $request->user()->id,
        'name' => $request->user()->name,
        'email' => $request->user()->email,
        'roles' => $request->user()->roles
    ]);
});

Route::get('/tickets/statistics', [TicketApiController::class, 'statistics']);
Route::get('/tickets', [TicketApiController::class, 'index']);
Route::post('/tickets', [TicketApiController::class, 'store']);
