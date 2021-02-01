<?php
use Illuminate\Http\Request;


$router->get('/', function () use ($router){
    return $router->app->version();
});



//Auth
Route::group(['prefix' => 'auth'], function () use ($router) 
{
   Route::post('register','AuthController@register');
   Route::post('login', 'AuthController@login');
   Route::get('logout', 'AuthController@logout');
   Route::get('user', 'AuthController@me');
});


// Menu Dashboard
Route::group(['prefix' => 'menu','as' => 'menu' ,'middleware' => ['auth','role']], function () use ($router){
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

// ,'middleware' => ['auth','role']
//Driver
Route::group(['prefix' => 'driver','as' => 'driver'], function () use ($router){
    Route::get('/pickup','OrderController@pickupList');
    Route::get('/pickup/history','OrderController@pickupHistory');
    Route::get('/pickup/{id}','OrderController@pickupShow');
    Route::post('/pickup/{id}','OrderController@pickupDone');
    Route::get('/delivery','OrderController@deliveryList');
    Route::get('/delivery/history','OrderController@deliveryHistory');  
    Route::get('/delivery/{id}','OrderController@deliveryShow');
    Route::post('/delivery/{id}','OrderController@deliveryDone');
   
});


//Order
Route::group(['prefix' => 'admin','as' => 'admin' ,'middleware' =>['auth','role']], function ()  {
    Route::get('order/','OrderController@index');
    Route::get('order/{id}','OrderController@show');
    Route::get('driver','DriverController@index');
    // Route::post('/','OrderController@store');
    // Route::post('/update','OrderController@update');
    // Route::post('/{id}','OrderController@destroy');
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


