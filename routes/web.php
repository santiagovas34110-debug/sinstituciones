<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InstitucionesController;
use App\Http\Controllers\ProfesorController;
use App\Http\Controllers\EstudiantesController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\UsersController;

 // ================= Autentificación ================
Route::get('login',[AuthController::class,'login'])->name('login');
Route::post('doLogin',[AuthController::class,'doLogin'])->name('doLogin');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('doRegister', [AuthController::class, 'doRegister'])->name('doRegister');

Route::middleware('auth')->group(function(){
    
    Route::get('logout',[AuthController::class,'logout'])->name('logout');
    // ================= ESCUELAS =================
    Route::get('escuelas',[InstitucionesController::class,'index'])->name("escuelas.index");
    Route::post('escuelas/create',[InstitucionesController::class,'create'])->name("escuelas.create");
    Route::get('escuelas/delete/{id}',[InstitucionesController::class,'delete'])->name('escuelas.delete');
    Route::post('escuelas/update',[InstitucionesController::class,'update'])->name('escuelas.update');
    
    Route::get('/',function(){
        return redirect()->route('home');
    });
    Route::get('home',[InstitucionesController::class,'home'])->name('home');

    // ================= PROFESORES =================
    Route::get('profesores',[ProfesorController::class,'profesores'])->name("profesores.index");
    Route::post('profesores/crear',[ProfesorController::class,'crearProfesor'])->name("profesores.store");
    Route::post('profesores/update',[ProfesorController::class,'updateProfesor'])->name("profesores.update");
    Route::get('profesores/eliminar/{id}',[ProfesorController::class,'eliminarProfesor'])->name("profesores.destroy");

     // ================= estudiantes =================
    Route::get('estudiantes',[EstudiantesController::class,'index'])->name("estudiantes.index");
    Route::post('estudiantes/crear',[EstudiantesController::class,'create'])->name("estudiantes.store");
    Route::post('estudiantes/update',[EstudiantesController::class,'update'])->name("estudiantes.update");
    Route::get('estudiantes/eliminar/{id}',[EstudiantesController::class,'delete'])->name("estudiantes.destroy");

    // ================= checklist =================
    Route::get('/checklist/panel', [ChecklistController::class, 'panel'])->name('checklist.panel');
    Route::get('/checklist/{id}', [ChecklistController::class, 'show'])->name('checklist.show');
    Route::post('/checklist/{id}/conexion', [ChecklistController::class, 'storeConexion'])->name('checklist.conexion');
    Route::put('/checklist/{id}/update', [ChecklistController::class, 'updateconexion'])->name('updateconexion');
    //Route::post('/checklist/experiencia', [ChecklistController::class, 'experiencia'])->name('checklist.experiencia');
    Route::put('/checklist/{id}/experiencia', [ChecklistController::class, 'updateExperiencia'])->name('checklist.updateExperiencia');
    Route::delete('/checklist/{id}/experiencia/fechas/{num}', [ChecklistController::class, 'deleteFechaExperiencia'])->name('checklist.deleteFechaExperiencia');
    Route::post('/checklist/{id}/reflexion', [ChecklistController::class, 'storeReflexion'])->name('checklist.reflexion');

    // ================== Usuarios ==================

    Route::get('users', [UsersController::class, 'index'])->name('users.index');
    Route::post('crearToken', [UsersController::class, 'crearToken'])->name('tokens.create');


});
