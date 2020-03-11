<?php
Route::group(['namespace' => 'User', 'middleware' => ['web']], function () {
  Route::any('admin/user/list/ajax', 'UserAjaxController@dataList');  //用户列表
  Route::post('admin/user/info','UserAjaxController@info');           //查看用户信息
  Route::any('admin/user/sync','UserAjaxController@userSync');        //用户同步

  Route::any('admin/channel/list/ajax', 'ChannelAjaxController@dataList');        //渠道列表
  Route::post('admin/channel/create/save', 'ChannelController@createSave');       //渠道添加保存
  Route::post('admin/channel/edit/save/{id}', 'ChannelController@editSave');      //渠道修改保存
  Route::post('admin/channel/delete', 'ChannelAjaxController@deleteData');        //渠道删除
  Route::post('admin/channel/valid', 'ChannelAjaxController@validChannelName');   //验证渠道名称

  Route::any('admin/tag/list/ajax', 'TagAjaxController@dataList');    //标签列表
  Route::post('admin/tag/valid', 'TagAjaxController@validTagName');   //验证标签名称
  Route::post('admin/tag/save', 'TagAjaxController@saveData');   //标签保存
  Route::post('admin/tag/delete', 'TagAjaxController@deleteData');   //标签删除
  Route::any('admin/user/relation/tag/{id}', 'TagController@relation');   //标签关联
  Route::any('admin/user/relation/tag/list/ajax', 'TagAjaxController@relationList');   //标签关联
  Route::any('admin/user/tag/delete', 'TagAjaxController@deleteUserTag');   //删除用户标签
  Route::any('admin/user/tag/data/save', 'TagAjaxController@saveUserTagRelationData');   //用户标签关联保存
  
  Route::any('admin/email/list/ajax', 'EmailAjaxController@dataList');        //邮箱列表
  Route::post('admin/email/create/save', 'EmailController@createSave');       //邮箱添加保存
  Route::post('admin/email/edit/save/{id}', 'EmailController@editSave');      //邮箱修改保存
  Route::post('admin/email/delete', 'EmailAjaxController@deleteData');        //邮箱删除
  Route::post('admin/email/valid', 'EmailAjaxController@validEmail');         //验证邮箱
});

Route::group(['namespace' => 'User', 'middleware' => ['admin']], function () {
  Route::any('admin/user/list', 'UserController@index'); //用户列表

  Route::any('admin/channel/list', 'ChannelController@index'); //渠道列表
  Route::any('admin/channel/create', 'ChannelController@create'); //渠道添加
  Route::any('admin/channel/edit/{id}', 'ChannelController@edit'); //渠道修改
  Route::any('admin/channel/view/{id}', 'ChannelController@view'); //渠道查看
  
  Route::any('admin/tag/list', 'TagController@index'); //标签列表
  
  Route::any('admin/email/list', 'EmailController@index'); //邮箱列表
  Route::any('admin/email/create', 'EmailController@create'); //邮箱添加
  Route::any('admin/email/edit/{id}', 'EmailController@edit'); //邮箱修改
});