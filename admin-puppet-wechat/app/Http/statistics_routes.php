<?php
Route::group(['namespace' => 'Statistics','middleware' => ['web']], function () {
  Route::get('test1','TestController@test1');
  Route::get('test2','TestController@test2');
});

Route::group(['namespace' => 'Statistics','middleware' => ['admin']], function () {
  Route::get('statistics/user/cumulate','UserCumulateController@index');
});