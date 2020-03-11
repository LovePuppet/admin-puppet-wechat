<?php
Route::group(['namespace' => 'Material', 'middleware' => ['web']], function () {
    Route::any('admin/material/text/list/ajax', 'TextAjaxController@dataList'); //微信文本素材ajax
    Route::any('admin/material/text/save', 'TextAjaxController@saveData');      //微信文本素材保存
    Route::any('admin/material/text/get', 'TextAjaxController@getData');        //微信文本素材获取 id
    Route::any('admin/material/text/crc_token/get', 'TextAjaxController@getDataByCrctoken');        //微信文本素材获取 crc_token
    Route::any('admin/material/text/delete', 'TextAjaxController@deleteData');  //微信文本素材删除
    
    Route::any('admin/material/image/list/ajax', 'ImageAjaxController@dataList'); //微信图片素材ajax
    Route::any('admin/material/image/delete', 'ImageAjaxController@deleteData');  //微信图片素材删除
    Route::any('admin/material/image/sync', 'ImageAjaxController@imageSync');     //微信图片素材同步
    Route::any('admin/material/image/get', 'ImageAjaxController@getData');     //获取微信图片
    
    Route::any('admin/material/news/list/ajax', 'NewsAjaxController@dataList'); //微信图文素材ajax
    Route::any('admin/material/news/delete', 'NewsAjaxController@deleteData');  //微信图文素材删除
    Route::any('admin/material/news/sync', 'NewsAjaxController@newsSync');     //微信图文素材同步
    Route::any('admin/material/news/get', 'NewsAjaxController@getData');     //获取微信图文
    
    Route::any('admin/material/file/list/ajax', 'FileAjaxController@dataList'); //文件列表ajax
    Route::any('admin/material/file/upload', 'FileAjaxController@fileUpload'); //文件上传
    Route::any('admin/material/file/delete', 'FileAjaxController@deleteData'); //文件删除
    
    Route::any('admin/material/video/list/ajax', 'VideoAjaxController@dataList'); //微信视频素材ajax
    Route::any('admin/material/video/delete', 'VideoAjaxController@deleteData');  //微信视频素材删除
    Route::any('admin/material/video/sync', 'VideoAjaxController@videoSync');     //微信视频素材同步
    Route::any('admin/material/video/get', 'VideoAjaxController@getData');        //获取微信视频
});

Route::group(['namespace' => 'Material', 'middleware' => ['admin']], function () {
    Route::get('admin/material/text/list', 'TextController@index'); //文本素材列表
    
    Route::get('admin/material/image/list', 'ImageController@index'); //图片素材列表
    
    Route::get('admin/material/news/list', 'NewsController@index'); //图文素材列表
    
    Route::get('admin/material/video/list', 'VideoController@index'); //视频素材列表
    
    Route::get('admin/material/file/list', 'FileController@index'); //文件列表
});