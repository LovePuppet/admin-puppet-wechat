<?php
Route::group(['namespace' => 'Home','middleware' => ['web']], function () {
  Route::any('test','TestController@index');//test页面

  Route::any('get/access_token','PuppetController@getWxAccessToken');
  Route::any('refresh/access_token','PuppetController@refreshWxAccessToken');
  Route::any('admin/lang/set/{lang}',function($lang){       //cookie设置
    $foreverCookie = Cookie::forever('puppet_lang',$lang);
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/admin';
    return redirect($referer)->withCookie($foreverCookie);
  });
});

Route::group(['namespace' => 'Home','middleware' => ['admin']], function () {
  Route::get('admin','HomeController@index');
});