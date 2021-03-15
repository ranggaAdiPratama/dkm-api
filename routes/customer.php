<?php

Route::group(['prefix' => 'customer','as' => 'customer' ,'middleware' =>['auth','role']], function ()  {
    Route::get('deliv-fee-list','DeliveryListController@index');
    
    Route::post('order','OrderController@createOrder');
    Route::post('cancel','OrderController@cancelStatus');
    Route::get('user','AuthController@me');
    Route::get('order-list','OrderController@orderListCustomer');
});
Route::post('customer/order-test','OrderController@createOrder');
Route::get('customer/','OrderController@customer');
Route::get('district-list','ApiRegionController@getDistrict');
Route::get('villages-list/{id}','ApiRegionController@getVillage');