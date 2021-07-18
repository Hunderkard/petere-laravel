<?php

use Illuminate\Support\Facades\Route;

//ag Ahora hay que importar los controladores.
use App\Http\Controllers\UserController;
use App\Http\Controllers\IngredienteController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\PlatoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
   //pl php artisan make:controller --resource. Todos los métodos.

/* cors */
Route::group(['middleware' => ['cors']], function(){

   Route::get('recetas', [PlatoController::class, 'getRecetas']);

   //ag Para poder usarlos de la nueva manera.
   Route::post('login',  [UserController::class, 'login']);
   Route::post('register', [UserController::class, 'register']);

   Route::post('sendPasswordReset', [ResetPasswordController::class, 'sendEmail']);
   Route::post('changePasswordReset', [ResetPasswordController::class, 'resetPassword']);
   Route::post('sendReserva', [ResetPasswordController::class, 'sendReserva']);
   Route::get('confirmarReserva', [ResetPasswordController::class, 'confirmarReserva']);
   Route::get('rechazarReserva', [ResetPasswordController::class, 'rechazarReserva']);

   Route::post('mesa', [MesaController::class, 'check']);
   Route::get('numero_mesas', [MesaController::class, 'contar']);
   Route::get('mesa_de/{nombre_usuario}', [UserController::class, 'mesa_de']);
   Route::put('sentar', [UserController::class, 'sentar']);

//ps 'jwt.verify', 'jwt.admin'
    Route::group(['middleware' => ['jwt.verify', 'jwt.admin']], function() {
        //ps jwt.verify o jwt.auth, ambas necesitarán en el header key:value Authorization: Bearer token.

        Route::post('imagen/ingrediente', [IngredienteController::class, 'reemplazarImagen']);
        Route::post('imagen/proveedor', [ProveedorController::class, 'reemplazarImagen']);
        Route::post('imagen/plato', [PlatoController::class, 'reemplazarImagen']);
        Route::put('compra', [IngredienteController::class, 'compra']);
        Route::put('perdida', [IngredienteController::class, 'perdida']);
        Route::get('caja', [MesaController::class, 'caja']);

        Route::get('receta/{plato}', [PlatoController::class, 'getReceta']);

        Route::get('caja', [MesaController::class, 'caja']);

        Route::resources([
        'ingredientes' => IngredienteController::class,
        'platos' => PlatoController::class,
        'proveedores' => ProveedorController::class,
        'usuarios' => UserController::class
        ], ['except'=> ['create', 'edit']]);
        
    });
//ps 'jwt.verify', 'jwt.camarero'
    // Route::group(['middleware' => ['jwt.verify', 'jwt.camarero']], function(){
        Route::get('cobrar/{mesa}', [MesaController::class, 'cobrar']);
        Route::get('limpiar/{mesa}', [MesaController::class, 'limpiar']);
        Route::get('anotar/{mesa}/{plato}', [MesaController::class, 'cuenta']);
        Route::post('fijar', [MesaController::class, 'cambiar']);
        Route::get('restar_plato/{plato_nombre}', [PlatoController::class, 'restar_ingredientes']);
    // });
//ps 'jwt.verify', 'jwt.cocinero'
    Route::group(['middleware' => ['jwt.verify', 'jwt.cocinero']], function(){
    });

   Route::get('ingredientes', [IngredienteController::class, 'index']);
   Route::get('platos', [PlatoController::class, 'index']);

});