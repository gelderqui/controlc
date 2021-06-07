<?php

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
    Route::group(['middleware'=>['guest']],function(){
        Route::get('/','Auth\LoginController@showLoginForm');
        Route::post('/login', 'Auth\LoginController@login')->name('login');
    });
    Route::group(['middleware'=>['auth']],function(){
        Route::post('/logout','Auth\LoginController@logout')->name('logout');
        Route::get('/main', function () {
            return view('contenido/contenido');
    })->name('main');

    Route::group(['middleware' => ['Almacenero']], function () {
        
        Route::get('/obtenerlogin', 'Auth\LoginController@obtenerlogin')->name('obtenerlogin');
        
        Route::get('/transferir', 'TransferirController@index');
        Route::post('/transferir/registrar', 'TransferirController@store');
        Route::get('/transferir/listar', 'TransferirController@listar');
        Route::put('/transferir/rechazar', 'TransferirController@rechazar');
        Route::put('/transferir/aceptar', 'TransferirController@aceptar');
        Route::get('/transferir/indexhora', 'TransferirController@indexhora');
        Route::get('/transferir/listarhora', 'TransferirController@listarhora');

        Route::get('/bodega/select', 'BodegaController@selectBodega');
     // Route::get('/bodega/buscar', 'BodegaController@buscarBodega');
    });


    Route::group(['middleware' => ['Vendedor']], function () {
        //Rutas de vendedor aun no asignadas
    });


    Route::group(['middleware' => ['Administrador']], function () {

        Route::get('/obtenerlogin', 'Auth\LoginController@obtenerlogin')->name('obtenerlogin');
        
        Route::get('/transferir', 'TransferirController@index');
        Route::post('/transferir/registrar', 'TransferirController@store');
        Route::post('/transferir/registraradmin', 'TransferirController@storeAdmin');
        Route::post('/transferir/registraringreso', 'TransferirController@storeIngreso');
        Route::get('/transferir/listar', 'TransferirController@listar');
        Route::put('/transferir/rechazar', 'TransferirController@rechazar');
        Route::put('/transferir/aceptar', 'TransferirController@aceptar');
        Route::get('/transferir/indexhoy', 'TransferirController@indexhoy');
        Route::get('/transferir/listarhora', 'TransferirController@listarhoy');
        Route::get('/transferir/fechahoy', 'TransferirController@fechahoy');
        Route::get('/transferir/indexfecha', 'TransferirController@indexfecha');
        Route::get('/transferir/total', 'TransferirController@total');

        Route::get('/bodega', 'BodegaController@index');
        Route::post('/bodega/registrar', 'BodegaController@store');
        Route::put('/bodega/actualizar', 'BodegaController@update');
        Route::put('/bodega/desactivar', 'BodegaController@desactivar');
        Route::put('/bodega/activar', 'BodegaController@activar');
        Route::get('/bodega/select', 'BodegaController@selectBodega');
        Route::get('/bodega/buscar', 'BodegaController@buscarBodega');
        Route::get('/bodega/listar', 'BodegaController@listarBodega');
        Route::get('/bodega/buscaringreso', 'BodegaController@buscarIngreso');
        Route::get('/bodega/listarfranquicia', 'BodegaController@listarFranquicia');
        Route::get('/bodega/listartodo', 'BodegaController@listartodo');
        
        Route::get('/user', 'UserController@index');
        Route::post('/user/registrar', 'UserController@store');
        Route::put('/user/actualizar', 'UserController@update');
        Route::put('/user/desactivar', 'UserController@desactivar');
        Route::put('/user/activar', 'UserController@activar');
        Route::get('/user/obtenerdetalle', 'UserController@obtenerDetalle');
        
        Route::get('/rol', 'RolController@selectRol'); 
    });
    
});


//Route::get('/home', 'HomeController@index')->name('home');
