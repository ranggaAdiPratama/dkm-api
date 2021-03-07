<?php
Route::group(['prefix' => 'admin','as' => 'admin' ,'middleware' =>['auth']], function ()  {
    //order reguler
    Route::get('order/pickup','AdminOrderController@index');
    Route::get('order/picked-up','AdminOrderController@finishPickupList');
    Route::get('order/delivery-assigned','AdminOrderController@readyToDeliveryList');
    Route::get('order/delivered','AdminOrderController@deliveredList');
    Route::get('order/re-delivery','AdminOrderController@reDeliveryList');
    Route::get('order/canceled','AdminOrderController@canceledList');
    Route::get('order/return','AdminOrderController@returnList');
    Route::get('order/history','AdminOrderController@orderHistory');
    Route::get('order/detail/{no_order}','AdminOrderController@detailOrder');
    Route::get('order/edit/{no_order}','AdminOrderController@editOrder');
    
    Route::post('order/update','AdminOrderController@updateOrder');
    Route::post('status','AdminOrderController@status');
    Route::post('order','AdminOrderController@createOrder');
    Route::post('/delivery-assign','AdminOrderController@deliveryAssign');



    //driver
    Route::get('driver','AdminOrderController@driverList');
    Route::get('driver/wallet','DriverController@driverWallet');
    Route::get('driver/wallet/{id}','DriverController@driverWalletDetail');
    Route::post('driver/filter','AdminOrderController@driverFilterList');
    Route::post('driver/placement','DriverController@driverPlacement');
    Route::post('driver/assign-redeliver','AdminOrderController@driverAssignRedelivery');
    Route::post('driver/set-balance','DriverController@setSaldo');

    // 
   
    

    //user
    Route::get('role','AuthController@roleList');
    Route::get('user','AuthController@userList');
    Route::get('user/customer','AdminOrderController@userListCustomer');
    Route::get('user/area','AdminOrderController@area');
    
});

Route::get('admin/order/show/{no_order}','AdminOrderController@show');