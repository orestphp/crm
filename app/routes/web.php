<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

// Logout
Route::get('/logout', [UserController::class, 'logout']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('role:admin,manager')->middleware('verified')->name('dashboard');

    // Users
    Route::get('/users', [UserController::class, 'index'])
        ->middleware('role:admin')->name('users.index');
    // Delete User
    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->middleware('role:admin')->name('users.destroy');

    // Tickets CRUD
    Route::resource('tickets', TicketController::class)
        ->middleware('role:admin, manager')->middleware('verified');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'update'])
        ->middleware('role:admin')->middleware('verified')
        ->name('tickets.destroy');

    // Customers
    Route::get('customers', [CustomerController::class, 'index'])
        ->middleware('role:admin, manager')->middleware('verified')
        ->name('customers.index');
    Route::delete('customers', [CustomerController::class, 'destroy'])
        ->middleware('role:admin')->middleware('verified')
        ->name('customers.destroy');
});



require __DIR__.'/auth.php';
