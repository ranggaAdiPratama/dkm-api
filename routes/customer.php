<?php

Route::group(['prefix' => 'customer','as' => 'customer' ,'middleware' =>['auth','role']], function ()  {
    Route::get('deliv-fee-list','DeliveryListController@index');
    Route::get('district-list','ApiRegionController@getDistrict');
    Route::get('villages-list/{id}','ApiRegionController@getVillage');
    Route::post('order','OrderController@create');
});