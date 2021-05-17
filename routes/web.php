<?php
use Illuminate\Http\Request;


$router->get('/', function () use ($router){
    return "Daeng Kurir API V1";
});

//Auth
Route::group(['prefix' => 'auth'], function () use ($router) 
{
   Route::post('register','AuthController@register');
   Route::post('update','AuthController@update');
   Route::post('update-address','AuthController@updateAddress');
   Route::post('login', 'AuthController@login');
   Route::get('logout', 'AuthController@logout');
   Route::get('user', 'AuthController@me');
   Route::get('refresh', 'AuthController@refresh');
   Route::get('change-status/{id}', 'AuthController@changeStatus');
   Route::post('change-password','AuthController@forgetPassword');
   Route::post('device-id','AuthController@getDeviceId');
});

 // Menu Dashboard
// Route::group(['prefix' => 'menu','as' => 'menu' ,'middleware' => ['auth','role']], function () use ($router){
//     Route::get('/','SidebarController@index');
//     Route::get('/{id}','SidebarController@show');
//     Route::post('/', 'SidebarController@store');
//     Route::post('/update', 'SidebarController@update');
//     Route::post('/{id}', 'SidebarController@destroy');
// });

//Delivery Address
Route::group(['prefix' => 'delivery-address'], function () {
    Route::get('/','DeliveryAddressController@index');
    Route::get('/{id}','DeliveryAddressController@show');
    Route::post('/','DeliveryAddressController@store');
    Route::post('/update','DeliveryAddressController@update');
    Route::post('/{id}','DeliveryAddressController@destroy');
});

include 'driver.php';
include 'driverExp.php';
include 'admin.php';
include 'customer.php';

Route::get('/photo/{name}','AuthController@getPhoto');
Route::get('/photo/product/{name}','AuthController@getPhotoProduct');
Route::get('city-list','ApiRegionController@getCity');
Route::get('district-list/{id}','ApiRegionController@getDistrict');
Route::get('villages-list/{id}','ApiRegionController@getVillage');
Route::get('customer/','OrderController@customer');
Route::get('region/','OrderController@region');

Route::post('send/','MailController@mail');
Route::get('/message', 'MessageController@sendMessage');
Route::post('/message', 'MessageController@sendMessage');







