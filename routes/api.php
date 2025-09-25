<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TareaController;

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
Route::get('/ping', function () {
    return response()->json(['message' => 'API OK']);
});

Route::prefix('usuarios')->group(function () {
    Route::post('/addUser', [UsuarioController::class, 'store']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Rutas para el controlador de usuarios, asignando nombres personalizados

Route::prefix('usuarios')->group(function () {
    Route::get('/listUsers', [UsuarioController::class, 'index']);
    Route::post('/addUser', [UsuarioController::class, 'store']);
    Route::get('/getUser/{id}', [UsuarioController::class, 'show']);
    Route::put('/updateUser/{id}', [UsuarioController::class, 'update']);
    Route::delete('/deleteUser/{id}', [UsuarioController::class, 'destroy']);
    Route::delete('/deleteTarea/{id}', [TareaController::class, 'destroy']); // Eliminar

});

Route::prefix('tareas')->group(function () {
    Route::get('/listTareas', [TareaController::class, 'index']); // Listar todas
    Route::post('/addTarea', [TareaController::class, 'store']); // Crear
    Route::get('/getTarea/{id}', [TareaController::class, 'show']); // Obtener una tarea
    Route::put('/updateTarea/{id}', [TareaController::class, 'update']); // Actualizar
    Route::delete('/deleteTarea/{id}', [TareaController::class, 'destroy']); // Eliminar
    Route::get('/tareas/exportPendientes', [TareaController::class, 'exportPendientes']);


});

Route::middleware('auth:sanctum')->get('/tareas/exportPendientes', [TareaController::class, 'exportPendientes']);



Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);