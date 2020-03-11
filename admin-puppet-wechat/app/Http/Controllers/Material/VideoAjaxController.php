<?php
namespace App\Http\Controllers\Material;
use App\Http\Controllers\ApiBaseController;
use Illuminate\Http\Request;
use App\Models\MaterialModels\Video;
use App\Components\WxTools;
class VideoAjaxController extends ApiBaseController{
  public function __construct(Request $request) {
    parent::__construct($request);
  }

  /**
   * 列表
   * ajax数据
   */
  public function dataList(){
    $oVideo = new Video();
    $result = $oVideo->getPageData($this->params);
    echo json_encode($result);
  }
    
  /**
   * 操作状态
   * 开启/关闭/删除
   */
  public function deleteData(Request $request){
    if(!$request->session()->has('user')){
      $this->code = 1;
      $this->msg = '登录已过时，请重新登录';
    }else{
      if(isset($this->params['id']) && intval($this->params['id']) > 0 && isset($this->params['status'])){
        $oVideo = new Video();
        $result = $oVideo->db_get(['video_id'=>['=',$this->params['id']]]);
        if(!empty($result)){
          if($oVideo->db_update('',$this->params['id'],['status'=>$this->params['status']])){
            WxTools::deleteWxMaterial($result['media_id']);
            $this->data = true;
          }else{
            $this->code = 1;
            $this->msg = '操作失败';
          }
        }else{
          $this->code = 1;
          $this->msg = '数据不存在';
        }
      }else{
        $this->code = 1;
        $this->msg = '缺少数据';
      }
    }
    $this->boot();
  }
 
  public function videoSync(){
    $params = ['type'=>'video','offset'=>0,'count'=>20];
    $result = WxTools::wxMaterial($params);
    \XLog::info("result=".$result, __FILE__, __FUNCTION__, __LINE__);
    $data = json_decode($result,true);
    if(isset($data['errcode'])){
      $this->code = 1;
      $this->msg = $data['errmsg'];
    }else{
      $this->saveVideo($data['item']);
      $page = ceil($data['total_count']/20);
      if($page > 1){
        for($i = 2;$i<=$page;$i++){
          $params = ['type'=>'image','offset'=>20*($i-1),'count'=>20];
          $result = WxTools::wxMaterial($params);
          $data = json_decode($result,true);
          $this->saveVideo($data['item']);
        }
      }
      $this->data = true;
    }
    $this->boot();
  }
    
  public function saveVideo($data){
    $result = false;
    if(!empty($data) && is_array($data)){
      $oVideo = new Video();
      foreach ($data as $val){
        $res = WxTools::getWxMaterial($val['media_id']);
        \XLog::info("res=".$res, __FILE__, __FUNCTION__, __LINE__);
        $arr = json_decode($res,true);
        if(!empty($arr)){
          $videoData = $oVideo->db_get(['media_id'=>['=',$val['media_id']],'status'=>['=',1]]);
          if(!empty($videoData)){
            $video = [];
            $video['title'] = $val['name'];
            $video['create_time'] = $val['update_time'];
            $video['status'] = 1;
            $video['description'] = $arr['description'];
            $video['url'] = $arr['down_url'];
            $oVideo->db_update('',$videoData['video_id'],$video);
          }else{
            $video = [];
            $video['title'] = $val['name'];
            $video['media_id'] = $val['media_id'];
            $video['create_time'] = $val['update_time'];
            $video['status'] = 1;
            $video['description'] = $arr['description'];
            $video['url'] = $arr['down_url'];
            $oVideo->db_insert($video);
          }
        }
      }
    }
    return $result;
  }
  
  public function getData(){
    if(isset($this->params['media_id']) && !empty($this->params['media_id'])){
      $arr = explode('#', $this->params['media_id']);
      $oVideo = new Video();
      $data = $oVideo->db_get(['media_id'=>['=',$arr[0]],'status'=>['=',1]]);
      if(!empty($data)){
        $this->data = $data;
      }else{
        $this->code = 1;
        $this->msg = '数据不存在';
      }
    }else{
      $this->code = 1;
      $this->msg = '缺少数据';
    }
    $this->boot();
  }
}