<?php
Route::group(['namespace' => 'Wechat', 'middleware' => ['web']], function () {
  Route::any('wechat/config', 'WxConfigController@index');                //微信基本配置
  Route::any('wechat/oauth', 'WxConfigController@oauth');                 //微信授权
  Route::any('wechat/oauth/post', 'WxConfigController@saveData');         //微信用户信息

  Route::any('admin/wechat/menu/list/ajax', 'WechatMenuAjaxController@dataList'); //微信菜单ajax
  Route::any('admin/wechat/menu/save', 'WechatMenuAjaxController@saveData');      //微信菜单保存
  Route::any('admin/wechat/menu/check', 'WechatMenuAjaxController@checkData');    //微信菜单设置检查接口
  Route::any('admin/wechat/menu/delete', 'WechatMenuAjaxController@deleteData');  //微信菜单删除
  Route::any('admin/wechat/material/list', 'WechatMenuAjaxController@materialList');  //加载更多素材内容
  Route::any('admin/wechat/material/save', 'WechatMenuAjaxController@saveMaterial');  //保存设置的素材
  Route::any('admin/wechat/menu/publish', 'WechatMenuAjaxController@publishMenu');    //微信菜单发布

  Route::any('admin/wechat/follow/new/save', 'FollowAjaxController@saveData');    //微信新用户关注保存设置
  Route::get('admin/wechat/follow/repeat', 'FollowController@repeat'); //微信重复关注回复
  Route::any('admin/wechat/follow/repeat/save', 'FollowAjaxController@repeatSaveData'); //微信重复关注回复保存设置
  
  Route::any('admin/group/send/list/ajax', 'GroupSendAjaxController@dataList'); //微信群发ajax
  Route::post('admin/group/send/all', 'GroupSendAjaxController@sendAll');  //群发发送
  Route::post('admin/group/preview', 'GroupSendAjaxController@preview');  //群发预览
});

Route::group(['namespace' => 'Wechat', 'middleware' => ['admin']], function () {
  Route::get('admin/wechat/menu/list', 'WechatMenuController@index'); //微信菜单
  Route::get('admin/wechat/follow/list', 'FollowController@index'); //微信关注回复
  Route::get('admin/group/send/list', 'GroupSendController@index'); //微信群发记录列表
  Route::get('admin/group/send/create', 'GroupSendController@create'); //新建群发
});