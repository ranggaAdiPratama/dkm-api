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
    Route::post('special-delivery-fee','DeliveryListController@specialDeliveryCount');
    Route::post('update-address','OrderController@updateAddress');
});
Route::get('deliv-fee-list','DeliveryListController@index');
Route::post('customer/delivery_fee','DeliveryListController@countDeliveryFee');
