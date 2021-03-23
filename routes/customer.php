<?php

Route::group(['prefix' => 'customer','as' => 'customer' ,'middleware' =>['auth','role']], function ()  {
    Route::get('deliv-fee-list','DeliveryListController@index');
    Route::post('order','OrderController@createOrder');
    Route::post('cancel','OrderController@cancelStatus');
    Route::post('/delivery_fee','DeliveryListController@countDeliveryFee');
    Route::get('user','AuthController@me');
    Route::get('order-list','OrderController@orderListCustomer');
});
Route::get('deliv-fee-list','DeliveryListController@index');
Route::post('customer/delivery_fee','DeliveryListController@countDeliveryFee');
Route::post('customer/order-test','OrderController@createOrder');
Route::get('customer/','OrderController@customer');
Route::get('district-list','ApiRegionController@getDistrict');
Route::get('villages-list/{id}','ApiRegionController@getVillage');