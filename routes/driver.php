<?php
//Driver
Route::group(['prefix' => 'driver','as' => 'driver','middleware' => ['auth','role']], function () use ($router){
    Route::get('/pickup','OrderController@pickupList');
    Route::get('/pickup/history','OrderController@pickupHistory');
    Route::get('/pickup/history/{id}','OrderController@pickupHistoryOrderList');
    Route::get('/pickup/order-list/{id}','OrderController@orderList');
    Route::get('/pickup/order-detail/{id}','OrderController@orderListDetail');
    Route::post('/pickup/status','OrderController@pickupStatus');
    Route::get('/delivery','OrderController@deliveryList');
    Route::get('/delivery/order-list/{id}','OrderController@DeliveryOrderList');  
    Route::get('/delivery/order-detail/{id}','OrderController@deliveryShow');
    Route::get('/delivery/history','OrderController@deliveryHistory');  
    Route::get('/delivery/history/{id}','OrderController@deliveryHistoryOrderList');
    Route::post('/delivery/status','OrderController@deliveryStatus');
    Route::get('/get-district','OrderController@getProvinsi');
    Route::get('/get-village','OrderController@getProvinsi');
    Route::get('/return','OrderController@returnList');
    Route::get('/return/order-list/{id}','OrderController@ReturnOrderList');
    Route::get('/return/order-detail/{id}','OrderController@ReturnOrderDetail');
    Route::get('/wallet','DriverController@driverTransaction');
    Route::post('/return-finish/{id}','OrderController@finishReturn');

});
