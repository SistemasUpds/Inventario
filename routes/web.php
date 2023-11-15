<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');


Route::group(['middleware' => ['auth', 'admin'], 'prefix' => 'admin'], function () {
    Route::get('/area/create', ['uses' => 'AreaController@create']);
    Route::post('/area',['uses' => 'AreaController@store'])->middleware('control');
    Route::get('/area/{id}/edit', ['uses' => 'AreaController@edit']);
    Route::post('/area/{id}/edit', ['uses' => 'AreaController@update'])->middleware('control');
    Route::delete('/area/{id}', ['uses' => 'AreaController@destroy'])->middleware('control');
    Route::get('/area/{id}/show', ['uses' => 'AreaController@show']);
    //Route::get('/item', 'ItemController@index');
    Route::get('/item/create/{id}', ['uses' => 'ItemController@create']);
    Route::post('/item', ['uses' => 'ItemController@store'])->middleware('control');
    Route::get('/item/{id}/edit', ['uses' => 'ItemController@edit']);
    Route::post('/item/{id}/edit', ['uses' => 'ItemController@update'])->middleware('control');
    Route::post('/item/{id}/delete', ['uses' => 'ItemController@destroy'])->middleware('control');
    Route::post('/item/{id}/eliminar', ['uses' => 'ItemController@delete'])->middleware('control');
    Route::get('/item/{id}/show', ['uses' => 'ItemController@show']);
    Route::get('/item/{id}/history', ['uses' => 'ItemController@history']);
    Route::post('area/{id}/qr', ['uses' => 'QrController@show']);
    Route::post('area/{id}/qr/create', ['uses' => 'QrController@create']);
    Route::get('/qrcode/{id}', ['uses' => 'QrController@show'])->name('qrcode.show');
    Route::post('/printQR', ['uses' => 'ItemController@printQR']);
    Route::get('/printPdf/{id}', ['uses' => 'ItemController@printPDf']);
    Route::get('/generar-pdf', ['uses' => 'AreaController@generarPDF'])->middleware('control');
    ///Usuarios
    Route::get('/users', ['uses' => 'UsersController@index']);
    Route::get('/users/create', ['uses' => 'UsersController@create']);
    Route::post('/users', ['uses' => 'UsersController@store'])->middleware('control');
    Route::get('/users/{id}/edit', ['uses' => 'UsersController@edit']);
    Route::post('/users/{id}/edit', ['uses' => 'UsersController@update'])->middleware('control');
    Route::delete('/users/{id}/delete', ['uses' => 'UsersController@destroy'])->middleware('control');
    Route::get('/users/{id}/activar', ['uses' => 'UsersController@activarCuenta'])->middleware('control');
    Route::get('/users/{id}/show', ['uses' => 'UsersController@show']);
    Route::delete('/users/{id}/eliminar', ['uses' => 'UsersController@eliminarCuenta'])->middleware('control');
    ///Sin permiso
    Route::get('/error', ['uses' => 'HomeController@sinPermiso']);
    // Contraseña
    Route::get('/password/{id}/reset', ['uses' => 'UsersController@viewResetPassword']);
    Route::post('/password/{id}/reset', ['uses' => 'UsersController@resetPassword'])->middleware('control');
    
    Route::get('/obtener-activos-por-tipo/{tipoId}', 'ItemController@obtenerActivosPorTipo');
    Route::get('/activo/create', 'AreaController@createActivo');
    Route::post('/activo',['uses' => 'AreaController@storeActivo'])->middleware('control');

    Route::get('/actividades', ['uses' => 'UsersController@actividadesUser']);
    
    Route::get('/material/create/{id}', ['uses' => 'ItemController@otroMaterial']);
    Route::post('/material/create', ['uses' => 'ItemController@storeMaterial'])->middleware('control');
    Route::post('/material/{id}/eliminar', ['uses' => 'ItemController@deleteMaterial'])->middleware('control');
    
    Route::get('/otro/material{id}/edit', ['uses' => 'ItemController@editMaterial']);
    Route::post('/otro/material{id}', ['uses' => 'ItemController@updateMaterial'])->middleware('control');
    Route::get('/otro/material{id}/show', ['uses' => 'ItemController@showMaterial']);
    Route::get('/otro/material/descargar{id}', ['uses' => 'ItemController@descargarMaterial'])->middleware('control');
});

Route::get('/vistaQR/{id}/{sigla?}', ['uses' => 'ItemController@vistaQR']);
