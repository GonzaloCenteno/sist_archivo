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

Route::get('/', function () {
    return view('auth/login');
});

$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->get('logout', 'Auth\LoginController@logout')->name('logout');

$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');

Auth::routes();

Route::group(['middleware' => 'auth'], function() 
{

    Route::get('$',function(){ echo 0;});//url auxiliar

    Route::group(['namespace' => 'configuracion'], function() 
    {
        Route::resource('usuarios', 'UsuariosController');
        Route::resource('tipo_archivo', 'Tipo_Archivo_Controller');
        Route::resource('roles', 'RolesController');
    });

    Route::group(['namespace' => 'permisos'], function() 
    {
        Route::resource('modulos', 'ModulosController');
        Route::resource('sub_modulos', 'Sub_ModulosController');
        Route::resource('permisos', 'Permisos_Modulo_UsuarioController');
    });

    Route::group(['namespace' => 'archivo'], function() 
    {
        Route::resource('archivos', 'ArchivoController');
        Route::resource('asignar_archivos', 'Asignar_Archivos_Controller');
        route::get('download/{id_archivo}', 'ArchivoController@descargar_archivos')->name('download');
        Route::resource('ver_archivos', 'Ver_Archivos_Controller');
        route::get('desc_archivos_asignados/{id_arch_pers}', 'Ver_Archivos_Controller@descargar_archivos_asignados')->name('desc_archivos_asignados');
    });

    Route::get('/home', 'HomeController@index')->name('usuarios');

});