<?php
namespace App\Http\Controllers\Wechat;
use App\Http\Controllers\ApiBaseController;
use Illuminate\Http\Request;
use App\Models\WechatModels\GroupSend;
class GroupSendAjaxController extends ApiBaseController{
    
  public function __construct(Request $request) {
    parent::__construct($request);
  }

  /**
   * 列表
   * ajax数据
   */
  public function dataList(){
    $oGroupSend = new GroupSend();
    $result = $oGroupSend->getPageData($this->params);
    echo json_encode($result);
  }
  
  /**
   * 微信群发
   */
  public function sendAll(){
    if(isset($this->params['tag_ids']) && isset($this->params['type'])
      && !empty($this->params['type']) && isset($this->params['content']) && !empty($this->params['content'])){
      $send = [];
      $data = [];
      if(empty($this->params['tag_ids'])){
        $oUser = new \App\Models\UserModels\User();
        $user_data = $oUser->getAllUser();
        if(!empty($user_data)){
          $user_ids = array_column($user_data,'user_id');
          $data['user_ids'] = json_encode($user_ids);
          \XLog::info("send.user_ids=".$data['user_ids'], __FILE__, __FUNCTION__, __LINE__);
          $send['touser'] = array_column($user_data,'openid');
        }
      }else{
        $data['tags'] = $this->params['tag_ids'];
        $all_user_id = [];
        $tag_id_arr = explode(',', $this->params['tag_ids']);
        $oUserTagRelation = new \App\Models\UserModels\UserTagRelation();
        foreach($tag_id_arr as $tag_id){
          $relation_data = $oUserTagRelation->getAllUserFromTag($tag_id);
          if(!empty($relation_data)){
            foreach ($relation_data as $v){
              $all_user_id[] = $v['user_id'];
            }
          }
        }
        $all_user_id = array_flip($all_user_id);//键值互换
        $all_user_id = array_flip($all_user_id);//键值互换
        $user_ids = array_values($all_user_id);//去掉键
        $data['user_ids'] = json_encode($user_ids);
        \XLog::info("send.user_ids=".$data['user_ids'], __FILE__, __FUNCTION__, __LINE__);
        $oThirdUser = new \App\Models\UserModels\ThirdUser();
        $third_data = $oThirdUser->getOpenidFromUserIds($user_ids);
        $send['touser'] = array_column($third_data,'openid');
      }
      \XLog::info("send.touser=".json_encode($send['touser']), __FILE__, __FUNCTION__, __LINE__);
      if(count($send['touser']) > 1){
        switch($this->params['type']){
          case 'text':
            $data['material_type'] = 1;
            $oText = new \App\Models\MaterialModels\Text();
            $text_data = $oText->db_get(['crc_token'=>['=',$this->params['content']],'status'=>['=',1]]);
            !empty($text_data) && $data['material_id'] = $text_data['text_id'];
            $send['msgtype'] = 'text';
            $send['text'] = ['content'=>$text_data['content']];
            break;
          case 'image':
            $data['material_type'] = 2;
            $oImage = new \App\Models\MaterialModels\Image();
            $image_data = $oImage->db_get(['media_id'=>['=',$this->params['content']],'status'=>['=',1]]);
            !empty($image_data) && $data['material_id'] = $image_data['image_id'];
            $send['msgtype'] = 'image';
            $send['image'] = ['media_id'=>$this->params['content']];
            break;
          case 'news':
            $data['material_type'] = 3;
            $oNews = new \App\Models\MaterialModels\News();
            $news_data = $oNews->db_get(['media_id'=>['=',$this->params['content']],'status'=>['=',1]]);
            !empty($news_data) && $data['material_id'] = $news_data['news_id'];
            $send['msgtype'] = 'mpnews';
            $send['mpnews'] = ['media_id'=>$this->params['content']];
            $send['send_ignore_reprint'] = 0;
            break;
          case 'video':
            $data['material_type'] = 6;
            $oVideo = new \App\Models\MaterialModels\Video();
            $arr = explode('#', $this->params['content']);
            $video_data = $oVideo->db_get(['media_id'=>['=',$arr[0]],'status'=>['=',1]]);
            !empty($news_data) && $data['material_id'] = $video_data['video_id'];
            $send['msgtype'] = 'mpvideo';
            $send['mpvideo'] = ['media_id'=>$arr[0]];
            $send['send_ignore_reprint'] = 0;
            break;
        }
        $params = json_encode($send,JSON_UNESCAPED_UNICODE);
        $data['content'] = $params;
        \XLog::info("send.params=".$params, __FILE__, __FUNCTION__, __LINE__);
        $result = \App\Components\WxGroupSend::send($params);
        \XLog::info("send.result=".$result, __FILE__, __FUNCTION__, __LINE__);
        $res = json_decode($result,true);
        $data['msg_id'] = isset($res['msg_id']) ? $res['msg_id'] : 0;
        $data['type'] = 1;
        $data['lang'] = 0;
        $data['customer_type'] = 0;
        $data['create_time'] = time();
        $data['status'] = $result;
        \XLog::info("GroupSend insert.data=".json_encode($data), __FILE__, __FUNCTION__, __LINE__);
        $oGroupSend = new GroupSend();
        $oGroupSend->db_insert($data);
        if($res['errcode'] == 0){
          $this->data = true;
        }else{
          $this->code = 1;
          $this->msg = $res['errmsg'];
        }
      }else{
        $this->code = 1;
        $this->msg = '群发最少需要2位用户';
      }
    }else{
      $this->code = 1;
      $this->msg = '缺少数据';
    }
    $this->boot();
  }
  
  /**
   * 微信群发预览
   */
  public function preview(){
    if(isset($this->params['type']) && !empty($this->params['type']) && isset($this->params['content']) && !empty($this->params['content'])){
      $preview['touser'] = 'oWEgF1s362hSuxWtPJ1ZQ_BUuxGY';
      switch($this->params['type']){
        case 'text':
          $preview['msgtype'] = 'text';
          $oText = new \App\Models\MaterialModels\Text();
          $text_data = $oText->db_get(['crc_token'=>['=',$this->params['content']],'status'=>['=',1]]);
          $preview['text'] = ['content'=>$text_data['content']];
          break;
        case 'image':
          $preview['msgtype'] = 'image';
          $preview['image'] = ['media_id'=>$this->params['content']];
          break;
        case 'news':
          $preview['msgtype'] = 'mpnews';
          $preview['mpnews'] = ['media_id'=>$this->params['content']];
          break;
      }
      $params = json_encode($preview,JSON_UNESCAPED_UNICODE);
      \XLog::info("preview.params=".$params, __FILE__, __FUNCTION__, __LINE__);
      $result = \App\Components\WxGroupSend::preview($params);
      \XLog::info("preview.result=".$result, __FILE__, __FUNCTION__, __LINE__);
      $res = json_decode($result,true);
      if($res['errcode'] == 0){
        $this->data = true;
      }else{
        $this->code = 1;
        $this->msg = $res['errmsg'];
      }
    }else{
      $this->code = 1;
      $this->msg = '缺少数据';
    }
    $this->boot();
  }
}