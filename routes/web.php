<?php
use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router){
    return $router->app->version();
});

// $router->get('/key', 'ExampleController@generateKey' );

// $router->post('optional[/{param}]',function($param=null){
//     return $param;
// });

// $router->group(['prefix' => 'admin','middleware' => 'age','namespace' => ''] , function () use ($router){
//     $router->get('home[/{age}]',function($age = null){
//         return 'This is Home';
//     });
// });

// $router->get('/fail',function(){
//     return 'Maaf umur anda belum mencukupi untuk mengakses halaman ini';
// });

Route::group(['prefix' => 'menu' ,'middleware' => ['auth','role']], function () use ($router){
    Route::get('/','SidebarController@index');
    Route::get('/{id}','SidebarController@show');
    Route::post('/', 'SidebarController@store');
    Route::post('/update', 'SidebarController@update');
    Route::post('/{id}', 'SidebarController@destroy');
});

// Route::group([

//     'prefix' => 'auth'

// ], function ($router) {

//     Route::post('login', 'AuthController@login');
//     Route::post('logout', 'AuthController@logout');
//     Route::post('refresh', 'AuthController@refresh');
//     Route::post('me', 'AuthController@me');

// });

$router->group(['prefix' => 'auth'], function () use ($router) 
{
   $router->post('register', 'AuthController@register');
   $router->post('login', 'AuthController@login');
   $router->get('logout', 'AuthController@logout');
});