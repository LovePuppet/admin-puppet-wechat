<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
class TagController extends Controller{
  public function index(){
    return view('user/tag/index', ['admin_tag_tree_menu' => true]);
  }
  
  /**
   * 用户关联标签页面
   */
  public function relation($id){
    $oUserTagRelation = new \App\Models\UserModels\UserTagRelation();
    $result = $oUserTagRelation->getAllUserFromTag($id);
    $ids = !empty($result) ? array_column($result,'user_id') : [];
    $ids = implode(',', $ids);
    $oChannel = new \App\Models\UserModels\Channel();
    $channels = $oChannel->getAllData();
    return view('user/tag/relation', ['admin_tag_tree_menu' => true,'id'=>$id,'ids'=>$ids,'channels'=>$channels]);
  }
}
