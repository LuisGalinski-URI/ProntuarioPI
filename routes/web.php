<?php

use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\PacienteController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'dashboard')->name('home');
Route::view('/dashboard', 'dashboard')->name('dashboard');

Route::resource('pacientes', PacienteController::class);
Route::get('/totem', [ConsultaController::class, 'totem'])->name('consultas.totem');
Route::patch('consultas/{consulta}/realizar', [ConsultaController::class, 'realizar'])
    ->name('consultas.realizar');
Route::resource('consultas', ConsultaController::class);
