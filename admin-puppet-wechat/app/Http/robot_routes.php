<?php
Route::group(['namespace' => 'Robot', 'middleware' => ['web']], function () {
    Route::any('admin/robot/list/ajax', 'RobotAjaxController@dataList');
    Route::any('admin/robot/create/save', 'RobotAjaxController@createSave');
    Route::any('admin/robot/edit/save/{id}', 'RobotAjaxController@editSave');
    Route::any('admin/robot/delete', 'RobotAjaxController@deleteData');
});

Route::group(['namespace' => 'Robot', 'middleware' => ['admin']], function () {
    Route::any('admin/robot/list', 'RobotController@index');
    Route::any('admin/robot/create', 'RobotController@create');
    Route::any('admin/robot/edit/{id}', 'RobotController@edit');
});

