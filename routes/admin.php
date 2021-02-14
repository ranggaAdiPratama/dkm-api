<?php
Route::group(['prefix' => 'admin','as' => 'admin' ,'middleware' =>['auth','role']], function ()  {
    Route::get('order/pickup','AdminOrderController@index');
    Route::get('order/finish-pickup','AdminOrderController@finishPickupList');
    Route::get('order/ready-to-deliver','AdminOrderController@readyToDeliveryList');
    Route::get('order/delivered','AdminOrderController@deliveredList');
    Route::get('order/canceled','AdminOrderController@canceledList');
    Route::get('order/return','AdminOrderController@returnList');
    Route::get('order/history','AdminOrderController@orderHistory');
    Route::get('driver','AdminOrderController@driverList');
    Route::get('driver/wallet','DriverController@driverWallet');
    Route::get('driver/wallet/{id}','DriverController@driverWalletDetail');
    Route::get('role','AuthController@roleList');
    Route::get('user','AuthController@userList');
    Route::post('status','AdminOrderController@status');
    Route::post('order','AdminOrderController@create');
});