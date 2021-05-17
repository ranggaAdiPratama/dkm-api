<?php
Route::group(['prefix' => 'admin','as' => 'admin' ,'middleware' =>['auth']], function ()  {
    //order reguler
    Route::get('order/all','AdminOrderController@allReguler');
    Route::get('order/pickup','AdminOrderController@index');
    Route::get('order/picked-up','AdminOrderController@finishPickupList');
    Route::get('order/delivery-assigned','AdminOrderController@readyToDeliveryList');
    Route::get('order/delivered','AdminOrderController@deliveredList');
    Route::get('order/re-delivery','AdminOrderController@reDeliveryList');
    Route::get('order/canceled','AdminOrderController@canceledList');
    Route::get('order/return','AdminOrderController@returnList');
    Route::get('order/history','AdminOrderController@orderHistory');
    Route::get('order/delivered-history','AdminOrderController@deliveredHistoryList');
    Route::get('order/canceled-history','AdminOrderController@canceledHistoryList');
    Route::get('order/detail/{no_order}','AdminOrderController@detailOrder');
    Route::get('order/edit/{no_order}','AdminOrderController@editOrder');

    //order express
    Route::get('order-exp/all','AdminOrderController@allExpress');
    Route::get('order-exp/pickup','AdminOrderController@pickingUpExp');
    Route::get('order-exp/picked-up','AdminOrderController@finishPickupListExp');
    Route::get('order-exp/delivery-assigned','AdminOrderController@readyToDeliveryListExp');
    Route::get('order-exp/delivered','AdminOrderController@deliveredListExp');
    Route::get('order-exp/re-delivery','AdminOrderController@reDeliveryListExp');
    Route::get('order-exp/canceled','AdminOrderController@canceledListExp');
    Route::get('order-exp/return','AdminOrderController@returnListExp');
    Route::get('order-exp/history','AdminOrderController@orderHistory');
    Route::get('order-exp/delivered-history','AdminOrderController@deliveredHistoryListExp');
    Route::get('order-exp/canceled-history','AdminOrderController@canceledHistoryListExp');
    Route::get('order-exp/detail/{no_order}','AdminOrderController@detailOrder');
    Route::get('order-exp/edit/{no_order}','AdminOrderController@editOrder');
    
    Route::post('order/update','AdminOrderController@updateOrder');
    Route::post('status-exp','AdminOrderController@statusExp');
    Route::post('status','AdminOrderController@status');
    Route::post('order','AdminOrderController@createOrder');
    Route::post('pickup/cancel-refund','AdminOrderController@pickupCancelRefund');
    Route::post('deliver/cancel-refund','AdminOrderController@deliverCancelRefund');
    Route::post('/delivery-assign','AdminOrderController@deliveryAssign');


    Route::get('/special-region','AdminOrderController@specialRegionList');
    Route::get('/special-region/delete/{id}','AdminOrderController@deleteSpecialRegion');
    Route::get('/city/delete/{id}','AdminOrderController@deleteCity');
    Route::post('/city','AdminOrderController@addCity');
    Route::post('/special-region','AdminOrderController@addSpecialRegion');
    Route::post('/special-region/update','AdminOrderController@updateSpecialRegion');

    //driver
    Route::get('driver','DriverController@driverList');
    Route::get('driver-exp','DriverController@driverListExp');
    Route::get('driver/wallet','DriverController@driverWallet');
    Route::get('driver/wallet-exp','DriverController@driverWalletExp');
    Route::get('driver/wallet/{id}','DriverController@driverWalletDetail');
    Route::get('driver/pull-balance/{id}','DriverController@pullBalance');
    Route::post('driver/wallet','DriverController@driverWalletFilter');
    Route::post('driver/filter','AdminOrderController@driverFilterList');
    Route::post('change-driver/filter','AdminOrderController@changeDriverFilterList');
    Route::post('driver/return-assign','AdminOrderController@driverAssignReturn');
    Route::post('change-driver-exp/filter','AdminOrderController@changeDriverFilterListExp');
    Route::post('delivery-driver/filter','AdminOrderController@DeliveryDriverFilterList');
    Route::post('driver/placement','DriverController@driverPlacement');
    Route::post('driver/assign-redeliver','AdminOrderController@driverAssignRedelivery');
    Route::post('driver/change-driver-pickup','AdminOrderController@changeDriverPickUp');
    Route::post('driver/change-driver-exp','AdminOrderController@changeDriverExp');
    Route::post('driver/change-driver-deliver','AdminOrderController@changeDriverDeliver');
    Route::post('driver/set-balance','DriverController@setSaldo');
    Route::post('driver/add-balance','DriverController@addSaldo');
    Route::post('driver-exp/set-balance','DriverController@setSaldoExp');
    Route::post('driver-exp/add-balance','DriverController@addSaldoExp');
    Route::post('driver/delivery-assign','AdminOrderController@driverDeliveryAssign');
    
    Route::get('region','AdminOrderController@region');
    Route::get('special-delivery-fee/{id}','AdminOrderController@specialDeliveryFee');
    Route::get('special-pickup-fee/{id}','AdminOrderController@specialPickupFee');

    //user
    Route::get('role','AuthController@roleList');
    Route::get('user','AuthController@userList');
    Route::get('user/customer','AdminOrderController@userListCustomer');
    Route::get('user/area','AdminOrderController@area');

    
    
});

Route::get('admin/order/show/{id}','AdminOrderController@show');
Route::post('admin/notif','AdminOrderController@sendNotif');