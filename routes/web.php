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
   Route::post('login', 'AuthController@login');
   Route::get('logout', 'AuthController@logout');
   Route::get('user', 'AuthController@me');
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
include 'admin.php';
include 'customer.php';

Route::get('/photo/{name}','AuthController@getPhoto');
Route::get('/photo/product/{name}','AuthController@getPhotoProduct');
Route::post('testInsert','OrderController@testInsert');







