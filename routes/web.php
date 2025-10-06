<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

Route::get('/', [Controller::class, 'formularioReceta'])->name('formulario.receta');
Route::post('/recetas', [Controller::class, 'procesarReceta']);
Route::get('/receta', [Controller::class, 'mostrarReceta'])->name('mostrar.receta');
