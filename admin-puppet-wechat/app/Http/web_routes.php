<?php
Route::group(['namespace' => 'Web', 'middleware' => ['web']], function () {
    Route::any('userinfo', 'UserInfoController@index');
});

