<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\ApiBaseController;
use Illuminate\Http\Request;
use App\Models\UserModels\Tag;
class TagAjaxController extends ApiBaseController {
  public function __construct(Request $request) {
    parent::__construct($request);
  }

  /**
   * 列表
   * ajax数据
   */
  public function dataList(){
    $oTag = new Tag;
    $result = $oTag->getPageData($this->params);
    echo json_encode($result);
  }
  
  /**
    * 验证渠道名是否存在
    */
  public function validTagName(){
    $tag_name = trim($this->params['name']);
    $id = intval($this->params['id']);
    $oTag = new Tag();
    $data = [];
    if($id > 0){
      $data = $oTag->db_get(['name'=>['=',$tag_name],'status'=>['=',1],'tag_id'=>['!=',$id]]);
    }else{
      $data = $oTag->db_get(['name'=>['=',$tag_name],'status'=>['=',1]]);
    }
    $this->data = !empty($data) ? true : false;
    $this->boot();
  }
  
  public function saveData(){
    $id = intval($this->params['id']);
    $tag_name = trim($this->params['name']);
    $oTag = new Tag();
    if($id > 0){
      if($oTag->db_update('',$id,['name'=>$tag_name])){
        $this->data = true;
      }else{
        $this->code = 1;
        $this->msg = '保存失败';
      }
    }else{
      if($oTag->db_insert(['name'=>$tag_name,'create_time'=> time()])){
        $this->data = true;
      }else{
        $this->code = 1;
        $this->msg = '保存失败';
      }
    }
    $this->boot();
  }
  
  public function deleteData(){
    $id = intval($this->params['id']);
    if($id > 0){
        $oTag = new Tag();
        if($oTag->db_update('',$id,['status'=>-1])){
          $this->data = true;
        }else{
          $this->code = 1;
          $this->msg = '保存失败';
        }
    }else{
      $this->code = 1;
      $this->msg = '数据不存在';
    }
    $this->boot();
  }
  
  /**
   * 删除用户标签
   */
  public function deleteUserTag(){
    if(isset($this->params['id']) && intval($this->params['id']) > 0){
      $oUserTagRelation = new \App\Models\UserModels\UserTagRelation();
      $oUserTagRelation->db_delete('',$this->params['id']);
      $this->data = true;
    }else{
      $this->code = 1;
      $this->msg = '缺少数据';
    }
    $this->boot();
  }
  
  /**
   * 关联数据列表
   */
  public function relationList(){
    $id = intval($this->params['tag_id']);
    if($id){
      $oUserTagRelation = new \App\Models\UserModels\UserTagRelation();
      $result = $oUserTagRelation->getPageData($this->params);
    }else{
      $result['data'] = [];
      $result['total'] = 0;
    }
    echo json_encode($result);
  }
  
  /**
   * 保存用户标签关联数据
   */
  public function saveUserTagRelationData(){
    if(isset($this->params['id']) && intval($this->params['id']) > 0 && isset($this->params['ids'])){
      //删除绑定该标签所有用户
      $oUserTagRelation = new \App\Models\UserModels\UserTagRelation();
      $oUserTagRelation->deleteAllUserFromTag($this->params['id']);
      $relation_ids = explode(',', $this->params['ids']);
      if(!empty($relation_ids)){
        foreach ($relation_ids as $relation){
          $data = [];
          $data['user_id'] = intval($relation);
          $data['tag_id'] = intval($this->params['id']);
          $data['create_time'] = time();
          $oUserTagRelation->db_insert($data);
        }
      }
      $this->data = true;
    }else{
      $this->code = 1;
      $this->msg = '缺少数据';
    }
    $this->boot();
  }
}