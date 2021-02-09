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
Route::group(['prefix' => 'driver','as' => 'driver','middleware' => ['auth','role']], function () use ($router){
    Route::get('/pickup','OrderController@pickupList');
    Route::get('/pickup/history','OrderController@pickupHistory');
    Route::get('/pickup/{id}','OrderController@pickupShow');
    Route::post('/pickup/status','OrderController@pickupStatus');
    Route::get('/delivery','OrderController@deliveryList');
    Route::get('/delivery/history','OrderController@deliveryHistory');  
    Route::get('/delivery/{id}','OrderController@deliveryShow');
    Route::post('/delivery/status','OrderController@deliveryStatus');
    Route::get('/get-district','OrderController@getProvinsi');
    Route::get('/get-village','OrderController@getProvinsi');
   
});

//Admin
Route::group(['prefix' => 'admin','as' => 'admin' ,'middleware' =>['auth','role']], function ()  {
    Route::get('order/pickup','OrderController@index');
    Route::get('order/finish-pickup','OrderController@finishPickupList');
    Route::get('order/ready-to-deliver','OrderController@readyToDeliveryList');
    Route::get('order/delivered','OrderController@deliveredList');
    Route::get('order/history','OrderController@orderHistory');
    Route::get('order/{id}','OrderController@show');
    Route::get('driver','DriverController@index');
    Route::get('role','AuthController@roleList');
    Route::get('user','AuthController@userList');
    Route::post('/pickup/status','OrderController@pickupStatus');
});


// Customer
Route::group(['prefix' => 'customer','as' => 'customer' ,'middleware' =>['auth','role']], function ()  {
    Route::get('deliv-fee-list','DeliveryListController@index');
    Route::get('district-list','ApiRegionController@getDistrict');
    Route::get('villages-list/{id}','ApiRegionController@getVillage');
    Route::post('order','OrderController@create');
});




