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

//Auth
Route::group(['prefix' => 'auth'], function () use ($router) 
{
   Route::post('register','AuthController@register');
   Route::post('login', 'AuthController@login');
   Route::get('logout', 'AuthController@logout');
});


// Menu Dashboard
Route::group(['prefix' => 'menu' ,'middleware' => ['auth','role']], function () use ($router){
    Route::get('/','SidebarController@index');
    Route::get('/{id}','SidebarController@show');
    Route::post('/', 'SidebarController@store');
    Route::post('/update', 'SidebarController@update');
    Route::post('/{id}', 'SidebarController@destroy');
});

//Delivery Address
Route::group(['prefix' => 'delivery-address'], function () {
    Route::get('/','DeliveryAddressController@index');
    Route::get('/{id}','DeliveryAddressController@show');
    Route::post('/','DeliveryAddressController@store');
    Route::post('/update','DeliveryAddressController@update');
    Route::post('/{id}','DeliveryAddressController@destroy');
});


//Order
Route::group(['prefix' => 'order'], function () {
    Route::get('/','OrderController@index');
    Route::get('/{id}','OrderController@show');
    // Route::post('/','OrderController@store');
    // Route::post('/update','OrderController@update');
    // Route::post('/{id}','OrderController@destroy');
});



