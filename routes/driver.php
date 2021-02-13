<?php
//Driver
Route::group(['prefix' => 'driver','as' => 'driver','middleware' => ['auth','role']], function () use ($router){
    Route::get('/pickup','OrderController@pickupList');
    Route::get('/pickup/history','OrderController@pickupHistory');
    Route::get('/pickup/order-list/{id}','OrderController@orderList');
    Route::get('/pickup/order-detail/{id}','OrderController@orderListDetail');
    Route::post('/pickup/status','OrderController@pickupStatus');
    Route::get('/delivery','OrderController@deliveryList');
    Route::get('/delivery/history','OrderController@deliveryHistory');  
    Route::get('/delivery/{id}','OrderController@deliveryShow');
    Route::post('/delivery/status','OrderController@deliveryStatus');
    Route::get('/get-district','OrderController@getProvinsi');
    Route::get('/get-village','OrderController@getProvinsi');
});
