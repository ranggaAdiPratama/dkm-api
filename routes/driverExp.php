<?php
//Driver
Route::group(['prefix' => 'driver-exp','as' => 'driver-exp','middleware' => ['auth','role']], function () use ($router){
    // Route::get('/pickup','OrderExpController@pickupList');
    // Route::get('/pickup/history','OrderExpController@pickupHistory');
    // Route::get('/pickup/history/{id}','OrderExpController@pickupHistoryOrderList');
    // Route::get('/pickup/order-list/{id}','OrderExpController@orderList');
    // Route::get('/pickup/order-detail/{id}','OrderExpController@orderListDetail');
    Route::post('/pickup/status','OrderExpController@pickupStatus');
    Route::get('/pickup/order-list','OrderExpController@PickupOrderList');
    Route::get('/pickup/order-detail/{id}','OrderExpController@orderListDetail');
    Route::get('/delivery','OrderExpController@deliveryList');
    Route::get('/delivery/order-list','OrderExpController@DeliveryOrderList');  
    Route::get('/delivery/order-detail/{id}','OrderExpController@deliveryShow');
    // Route::get('/delivery/history','OrderExpController@deliveryHistory');  
    Route::get('/delivery/history','OrderExpController@deliveryHistoryOrderList');
    Route::post('/delivery/status','OrderExpController@deliveryStatus');
    Route::get('/get-district','OrderExpController@getProvinsi');
    Route::get('/get-village','OrderExpController@getProvinsi');
    Route::get('/return','OrderExpController@returnList');
    Route::get('/return/order-list/{id}','OrderExpController@ReturnOrderList');
    Route::get('/return/order-detail/{id}','OrderExpController@ReturnOrderDetail');
    Route::get('/wallet','DriverController@driverTransaction');
    Route::get('/change/{id}','OrderExpController@driverChange');
    Route::post('/return-finish/{id}','OrderExpController@finishReturn');


});
