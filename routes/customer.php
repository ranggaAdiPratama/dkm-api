<?php

Route::group(['prefix' => 'customer','as' => 'customer' ,'middleware' =>['auth','role']], function ()  {
    Route::get('deliv-fee-list','DeliveryListController@index');
    Route::post('order','OrderController@createOrder');
    Route::post('order-exp','OrderExpController@createOrder');
    Route::post('cancel/{id}','OrderController@cancelStatus');
    // Route::post('/delivery_fee','DeliveryListController@countDeliveryFee');
    Route::get('user','AuthController@me');
    Route::get('order-list','OrderController@orderListCustomer');
    Route::get('order-list-exp','OrderExpController@orderListCustomerExp');
    Route::get('history-list','OrderExpController@historyOrderListCustomer');
    Route::get('history-detail/{id}','OrderExpController@historyDetailCustomer');
    Route::get('tracker/{status}','OrderController@customerTracker');
    Route::get('tracker/detail/{id_order}','OrderController@trackerDetailCustomer');
    Route::get('pickup-fee','DeliveryListController@specialPickupFee');
    Route::get('special-delivery-fee/{id}','DeliveryListController@specialDeliveryFee');
    Route::get('pickup-address-list','OrderController@getAddress');
    Route::get('pickup-address-active','OrderController@getAddressActive');
    Route::get('pickup-address/{id}','OrderController@getPickupAddressById');
    Route::get('address/{id}','OrderController@getAddressById');
    Route::get('delete-pickup-address/{id}','OrderController@deletePickupAddress');
    Route::post('change-pickup-address','OrderController@changePickupAddress');
    Route::post('special-delivery-fee','DeliveryListController@specialDeliveryCount');
    Route::post('update-address','OrderController@updateAddress');
    Route::post('add-pickup-address','OrderController@addPickupAddress');
    Route::post('update-pickup-address','OrderController@updatePickupAddress');
   

});
Route::get('deliv-fee-list','DeliveryListController@index');
Route::post('customer/delivery_fee','DeliveryListController@countDeliveryFee');
