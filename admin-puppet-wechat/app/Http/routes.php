<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/',function(){
  return redirect('admin');
});
require __DIR__.'/login_routes.php';                //后台权限管理的路由文件
require __DIR__.'/home_routes.php';                 //首页的路由文件
require __DIR__.'/wechat_routes.php';               //微信路由文件
require __DIR__.'/material_routes.php';             //微信素材路由文件
require __DIR__.'/user_routes.php';                 //用户路由文件
require __DIR__.'/robot_routes.php';                //机器人路由文件
require __DIR__.'/web_routes.php';                  //web路由文件
require __DIR__.'/statistics_routes.php';           //数据统计路由文件