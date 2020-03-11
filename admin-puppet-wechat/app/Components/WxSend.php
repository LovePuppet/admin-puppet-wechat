<?php
namespace App\Components;
use App\Components\Tools;
use App\Models\MaterialModels\Text;
use App\Models\WechatModels\SendRecord;

/**
 * 发送消息
 */
class WxSend{
    /**
     * 
     * @param type $oPost       微信消息对象
     * @param type $content     回复的内容
     * @param type $fid         上级消息编号
     * @param type $is_customer_type   是否收集用户的类型  1.广告代理方 2.客户方
     * @param type $is_end      是否已结束
     * @param type $userinfo    是否收集用户信息 姓名 + 邮箱
     * @return boolean          
     */
    public static function multipleMsgSend($oPost,$content,$fid = 0,$is_customer_type = 0,$is_end = 0,$userinfo = 0){
        \XLog::info("in.oPost=".json_encode($oPost,JSON_UNESCAPED_UNICODE).", content=".$content, __FILE__, __FUNCTION__, __LINE__);
        if(!empty($content)){
            $msg_arr = json_decode($content,true);
            if(!empty($msg_arr) && is_array($msg_arr)){
                foreach ($msg_arr as $msg){
                    if(!empty($msg['content'])){
                        $params = self::msgHandle($oPost->FromUserName,$msg['msgtype'],$msg['content']);
                        WxTools::transmitCustom($params);
                        sleep(1);
                        continue;
                    }
                }
                if($fid){
                    Tools::wxMsgRecord($oPost->FromUserName,['fid'=>$fid,'is_customer_type'=>$is_customer_type,'is_end'=>$is_end,'userinfo'=>$userinfo]);
                }
            }
        }
        return TRUE;
    }
    
    /**
     * 消息处理
     */
    public static function msgHandle($openid,$type,$content){
      \XLog::info("in.openid=".$openid.", type=".$type.", content=".$content, __FILE__, __FUNCTION__, __LINE__);
      $result = [];
      switch ($type){
        case 'text':
          $oText = new Text();
          $textData = $oText->db_get(['crc_token'=>['=',$content],'status'=>['=',1]]);
          if(!empty($textData)){
            $result['touser'] = (string)$openid;
            $result['msgtype'] = 'text';
            $text_content = Tools::replaceNickname($textData['content'],$openid);
            $result['text'] = ['content'=>$text_content];
          }
          break;
        case 'image':
          $result['touser'] = (string)$openid;
          $result['msgtype'] = 'image';
          $result['image'] = ['media_id'=>$content];
          break;
        case 'news':
          $result['touser'] = (string)$openid;
          $result['msgtype'] = 'mpnews';
          $result['mpnews'] = ['media_id'=>$content];
          break;
        case 'video':
          $result['touser'] = (string)$openid;
          $result['msgtype'] = 'video';
          $oVideo = new \App\Models\MaterialModels\Video();
          $data = $oVideo->db_get(['media_id'=>['=',$content]]);
          if(!empty($data)){
            $result['video'] = ['media_id'=>$content,'thumb_media_id'=>$content,'title'=>$data['title'],'description'=>$data['description']];
          }
          break;
      }
      $cache = Tools::wxMsgRecord($openid);
      if(isset($cache['lang'])){
        switch ($cache['lang']){
          case 1:
            $result['customservice'] = ['kf_account'=>'kf2002@sprosoft'];
            break;
          case 2:
            $result['customservice'] = ['kf_account'=>'kf2001@sprosoft'];
            break;
        }
      }
      //发送消息记录
      $oSendRecord = new SendRecord();
      $oSendRecord->insertSendRecord($result);
      \XLog::info("out.result=".json_encode($result,JSON_UNESCAPED_UNICODE), __FILE__, __FUNCTION__, __LINE__);
      return $result;
    }
    
    /**
     * 定时消息发送处理
     */
    public static function timingMsgSend($openid,$content){
//        \XLog::info("in.openid=".$openid.", content=".json_encode($content,JSON_UNESCAPED_UNICODE), __FILE__, __FUNCTION__, __LINE__);
        if(!empty($content)){
            $oText = new Text();
            $oSendRecord = new SendRecord();
            foreach ($content as $msg){
                if(!empty($msg['text'])){//text
                    $textData = $oText->db_get(['crc_token'=>['=',$msg['text']['content']],'status'=>['=',1]]);
                    if(!empty($textData)){
                        $text_content = Tools::replaceNickname($textData['content'],$openid);
                        $msg['text']['content'] = $text_content;
                    }
                }
                //发送消息记录
                $oSendRecord->insertSendRecord($msg);
                WxTools::transmitCustom($msg);
                sleep(1);
                continue;
            }
        }
        return TRUE;
    }
}
