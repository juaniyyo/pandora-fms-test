<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AppointmentController;

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

/** Recurso de las reservas */
Route::Resource('/appointments', AppointmentController::class);

/**Recurso del usuario */
Route::Resource('/users', UserController::class);

/** Compobar la existencia del DNI */
Route::post('/users/check-dni', [UserController::class, 'checkDni']);

/**Enviar email de confirmacion */
Route::post('/users/send-confirmation-email', [UserController::class, 'sendConfirmationEmail']);
