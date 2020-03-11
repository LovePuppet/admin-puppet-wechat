<?php
namespace App\Components;
use Cache;
use Carbon\Carbon;
use App\Models\MaterialModels\Text;
use App\Models\RobotModels\Robot;
use App\Models\WechatModels\ReceiveRecord;
/**
 * 微信相关model类
 * @author 郭钊林
 */
class WxTools{
  public static $token = 'puppetwechat';
  public static $encodingAESKey = 'AHz893GTLv2Wak5H7k7eHxCUS4JpFOEW5cTbyMSI9lp';
  public static $appId = 'wx4e18739bd96e3562';
  public static $appSecret = '38ceb9e5849810f78f6898f76de821c1';

  public static function valid($params){
    \XLog::info("in.params=".json_encode($params,JSON_UNESCAPED_UNICODE), __FILE__, __FUNCTION__, __LINE__);
    $result = false;
    $signature = trim($params['signature']);
    $timestamp = trim($params['timestamp']);
    $nonce = trim($params['nonce']);
    if(isset($signature) && isset($timestamp) && isset($nonce)){
      $tmpArr = array(self::$token,$timestamp,$nonce);
      sort($tmpArr,SORT_STRING);
      $tmpStr = implode($tmpArr);
      $tmpStr = sha1($tmpStr);
      if($tmpStr == $signature){
        $result = true;
      }
    }
    \XLog::info("out.result=".$result, __FILE__, __FUNCTION__, __LINE__);
    return $result;
  }

  public static function wxAccessToken($refresh = false){
//        \XLog::info("in.refresh=".$refresh, __FILE__, __FUNCTION__, __LINE__);
      $result = Cache::get('access_token');
      if(!empty($result)){
          if($refresh){
              Cache::forget('access_token');
              self::wxAccessToken();
          }
          $result = unserialize($result);
      }else{
          $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.self::$appId.'&secret='.self::$appSecret;
          $data = HttpTools::open($url,'GET',false,false,true);
          $res = json_decode($data,TRUE);
          $result = (isset($res['access_token']) ? $res['access_token'] : FALSE);
          $expiresAt = Carbon::now()->addHours(1)->addMinutes(50);//缓存1小时50分钟    6600s
          Cache::put('access_token', serialize($result),$expiresAt);
      }
//        \XLog::info("out.access_token=".$result, __FILE__, __FUNCTION__, __LINE__);
      return $result;
  }

  /**
  * 接收
  *
  * @param String $strPost 提交数据
  * @return int 新ID
  */
  public static function Receive($strPost) {
      \XLog::info("in.strPost=".json_encode($strPost,JSON_UNESCAPED_UNICODE), __FILE__, __FUNCTION__, __LINE__);
      if (!empty($strPost)) {
          $oPost = simplexml_load_string($strPost,'SimpleXMLElement',LIBXML_NOCDATA);
          $RX_TYPE = trim($oPost->MsgType);
          switch ($RX_TYPE){
              case "event":
                if(!isset($oPost->MenuId)){  //不是微信菜单事件
                  $oReceiveRecord = new ReceiveRecord();
                  $oReceiveRecord->insertReceiveRecord($oPost);//记录用户消息
                  Tools::wxMsgRecord($oPost->FromUserName,['fid'=>0,'is_lang'=>1,'is_customer_type'=>0,'is_end'=>0,'userinfo'=>0,'timing'=>0]);//重置消息起点
                }
                $result = self::receiveEvent($oPost);
                return $result;
                break;
              case "text"://接收文本消息
//                    $cache = Tools::wxMsgRecord($oPost->FromUserName);
//                    if(!empty($cache)){
//                        Tools::wxMsgRecord($oPost->FromUserName,['fid'=>0,'is_lang'=>1]);//重置消息起点
//                        $oFollow = new \App\Models\WechatModels\Follow();
//                        $data = $oFollow->getFollowData($oPost->FromUserName);
//                        if(!empty($data)){
//                            WxSend::multipleMsgSend($oPost,$data['content']);
//                        }
//                    }
                  $oReceiveRecord = new ReceiveRecord();
                  $oReceiveRecord->insertReceiveRecord($oPost);//记录用户消息
                  $oRobot = new Robot();
                  $oRobot->getReplyContent($oPost);
                  $result = 'success';
                  return $result;
                  break;
              case "image"://接收图片消息
//                    $result = self::receiveText($oPost);
                  $result = 'success';
                  return $result;
                  break;
              case "voice"://接收语音消息
//                    $result = self::transmitKefu($oPost);
                  $result = 'success';
                  return $result;
                  break;
              case "video"://接收视频消息
//                    $result = self::receiveText($oPost);
                  $result = 'success';
                  return $result;
                  break;
              case "shortvideo"://接收小视频消息
//                    $result = self::receiveText($oPost);
                  $result = 'success';
                  return $result;
                  break;
              case "location"://接收地理位置消息
//                    $result = self::receiveText($oPost);
                  $result = 'success';
                  return $result;
                  break;
              case "link"://接收链接消息
//                    $result = self::receiveText($oPost);
                  $result = 'success';
                  return $result;
                  break;
              default:
                  break;
          }
      } else {
          return false;
      }
      return true;
  }

  /**
   * 接收文本消息
   */
  public static function receiveText($object){
      \XLog::info("in.object=".json_encode($object,JSON_UNESCAPED_UNICODE), __FILE__, __FUNCTION__, __LINE__);
      //接收到文本消息
      //自动回复，或者关键词响应，或者转客服
      $content = '微信正在努力开发中，敬请期待';
      return self::transmitText($object, $content);
  }

  /**
  * 事件处理
  *
  * @param Object $oPost 提交对象
  * @return String 返回消息
  */
  public static function receiveEvent($oPost){
    \XLog::info("in.oPost=".json_encode($oPost,JSON_UNESCAPED_UNICODE), __FILE__, __FUNCTION__, __LINE__);
    switch ($oPost->Event){
      case "subscribe"://关注，和扫码关注
        //关注处理
        $oFollow = new \App\Models\WechatModels\Follow();
        $data = [];
        $eventKey = trim($oPost->EventKey);
        if(!empty($eventKey)){
          $eventKey = str_replace('qrscene_','', $eventKey);
          $data = $oFollow->getFollowData($oPost->FromUserName,$eventKey);
        }else{
          $data = $oFollow->getFollowData($oPost->FromUserName);
        }
        if(!empty($data)){
          WxSend::multipleMsgSend($oPost,$data['content']);
        }
        $result = 'success';
        return $result;
        break;
      case "unsubscribe"://取消关注
        Tools::cleanWxMsgRecord($oPost->FromUserName);
        $oRecord = new \App\Models\WechatModels\Record();
        $oRecord->deleteRecord($oPost->FromUserName);
        $result = 'success';
        return $result;
        break;
      case "SCAN"://扫码事件
        $result = 'success';
        return $result;
        break;
      case "LOCATION"://上报地理位置事件
        $result = 'success';
        return $result;
        break;
      case "CLICK"://自定义菜单事件-点击菜单拉取消息时的事件推送
        $oText = new Text();
        $eventKey = trim($oPost->EventKey);
        if($eventKey == 'PuppetRobot'){
          Tools::wxMsgRecord($oPost->FromUserName,['fid'=>0,'is_lang'=>1,'is_customer_type'=>0,'is_end'=>0,'userinfo'=>0,'timing'=>0]);//重置消息起点
          //关注处理
          $oFollow = new \App\Models\WechatModels\Follow();
          $data = [];
          $eventKey = trim($oPost->EventKey);
          if(!empty($eventKey)){
            $eventKey = str_replace('qrscene_','', $eventKey);
            $data = $oFollow->getFollowData($oPost->FromUserName,$eventKey);
          }else{
            $data = $oFollow->getFollowData($oPost->FromUserName);
          }
          if(!empty($data)){
            WxSend::multipleMsgSend($oPost,$data['content']);
          }
          $result = 'success';
        }else{
          $content = $oText->getContent($oPost->EventKey);
          $content = Tools::replaceNickname($content,$oPost->FromUserName,TRUE);
          $result = self::transmitText($oPost, $content);
        }
        return $result;
        break;
      case "VIEW"://自定义菜单事件-点击菜单跳转链接时的事件推送
        $result = 'success';
        return $result;
        break;
      default:
        $result = 'success';
        return $result;
        break;
    }
    return $result;
  }

  /**
  * 文字回复
  * @param Object $object 提交对象
  * @return String 返回消息
  */
  public static function transmitText($object, $content){
      \XLog::info("in.content=".$content, __FILE__, __FUNCTION__, __LINE__);
      $replyXml = "<xml>"
               . "<ToUserName><![CDATA[%s]]></ToUserName>"
               . "<FromUserName><![CDATA[%s]]></FromUserName>"
               . "<CreateTime>%s</CreateTime>"
               . "<MsgType><![CDATA[text]]></MsgType>"
               . "<Content><![CDATA[%s]]></Content>"
               . "</xml>";
      return sprintf($replyXml, $object->FromUserName, $object->ToUserName, time(), $content);
  }

  /**
  * 图片回复
  * @param Object $object 提交对象
  * @return String 返回消息
  */
  public static function transmitImage($object, $mediaId){
      \XLog::info("in.object=".json_encode($object,JSON_UNESCAPED_UNICODE).", mediaId=".$mediaId, __FILE__, __FUNCTION__, __LINE__);
      $replyXml = "<xml>"
               . "<ToUserName><![CDATA[%s]]></ToUserName>"
               . "<FromUserName><![CDATA[%s]]></FromUserName>"
               . "<CreateTime>%s</CreateTime>"
               . "<MsgType><![CDATA[image]]></MsgType>"
               . "<Image><MediaId><![CDATA[%s]]></MediaId></Image>"
               . "</xml>";
      return sprintf($replyXml, $object->FromUserName, $object->ToUserName, time(), $mediaId);
  }

  /**
  * 语音回复
  * @param Object $object 提交对象
  * @return String 返回消息
  */
  public static function transmitVoice($object, $mediaId){
      \XLog::info("in.object=".json_encode($object,JSON_UNESCAPED_UNICODE).", mediaId=".$mediaId, __FILE__, __FUNCTION__, __LINE__);
      $replyXml = "<xml>"
               . "<ToUserName><![CDATA[%s]]></ToUserName>"
               . "<FromUserName><![CDATA[%s]]></FromUserName>"
               . "<CreateTime>%s</CreateTime>"
               . "<MsgType><![CDATA[Voice]]></MsgType>"
               . "<Voice><MediaId><![CDATA[%s]]></MediaId></Voice>"
               . "</xml>";
      return sprintf($replyXml, $object->FromUserName, $object->ToUserName, time(), $mediaId);
  }

  /**
  * 视频回复
  * @param Object $object 提交对象
  * @return String 返回消息
  */
  public static function transmitVideo($object, $mediaId,$title,$des){
      \XLog::info("in.object=".json_encode($object,JSON_UNESCAPED_UNICODE).", mediaId=".$mediaId.", title=".$title.", des=".$des, __FILE__, __FUNCTION__, __LINE__);
      $replyXml = "<xml>"
               . "<ToUserName><![CDATA[%s]]></ToUserName>"
               . "<FromUserName><![CDATA[%s]]></FromUserName>"
               . "<CreateTime>%s</CreateTime>"
               . "<MsgType><![CDATA[Video]]></MsgType>"
               . "<Video><MediaId><![CDATA[%s]]></MediaId>"
               . "<Title><![CDATA[%s]]></Title>"
               . "<Description><![CDATA[%s]]></Description></Video>"
               . "</xml>";
      return sprintf($replyXml, $object->FromUserName, $object->ToUserName, time(), $mediaId,$title,$des);
  }

  /**
  * 图文回复
  * @param Object $object 提交对象
  * @return String 返回消息
  */
  public static function transmitNews($object, $newsArr){
      \XLog::info("in.object=".json_encode($object,JSON_UNESCAPED_UNICODE).", newsArr=".json_encode($newsArr,JSON_UNESCAPED_UNICODE), __FILE__, __FUNCTION__, __LINE__);
      $itemStr = "";
      //定义item模板
      $itemXml = "<item>"
          ."<Title><![CDATA[%s]]></Title>"
          ."<Description><![CDATA[%s]]></Description>"
          ."<PicUrl><![CDATA[%s]]></PicUrl>"
          ."<Url><![CDATA[%s]]></Url>"
          ."</item>";
      foreach($newsArr as $item){
          $itemStr .= sprintf($itemXml,$item['Title'],$item['Description'],$item['PicUrl'],$item['Url']);
      }
      $replyXml = "<xml>"
          ."<ToUserName><![CDATA[%s]]></ToUserName>"
          ."<FromUserName><![CDATA[%s]]></FromUserName>"
          ."<CreateTime>%s</CreateTime>"
          ."<MsgType><![CDATA[news]]></MsgType>"
          ."<ArticleCount>".count($newsArr)."</ArticleCount>"
          ."<Articles>".$itemStr."</Articles>"
          ."</xml>";
      return sprintf($replyXml,$obj->FromUserName,$obj->ToUserName, time());
  }

  /**
  * 客服接口
  *
  * @param Object $object 提交对象
  * @return String 返回消息
  */
  public static function transmitKefu($object){
      \XLog::info("in.object=".json_encode($object,JSON_UNESCAPED_UNICODE), __FILE__, __FUNCTION__, __LINE__);
      $replyXml = "<xml>"
               . "<ToUserName><![CDATA[%s]]></ToUserName>"
               . "<FromUserName><![CDATA[%s]]></FromUserName>"
               . "<CreateTime>%s</CreateTime>"
               . "<MsgType><![CDATA[transfer_customer_service]]></MsgType>"
               . "</xml>";
      return sprintf($replyXml, $object->FromUserName, $object->ToUserName, time());
  }

  /**
   * 客服发送消息接口
   */
  public static function transmitCustom($params){
//        \XLog::info("in.params=".json_encode($params,JSON_UNESCAPED_UNICODE), __FILE__, __FUNCTION__, __LINE__);
      $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.self::wxAccessToken();
      return HttpTools::open($url,'POST',json_encode($params,JSON_UNESCAPED_UNICODE),false,true);
//        return HttpTools::https_post($url, json_encode($params,JSON_UNESCAPED_UNICODE));
  }

  /**
   * 获取微信素材列表
   */
  public static function wxMaterial($params){
      \XLog::info("in.params=".json_encode($params,JSON_UNESCAPED_UNICODE), __FILE__, __FUNCTION__, __LINE__);
      $url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.self::wxAccessToken();
      return HttpTools::open($url,'POST',json_encode($params),false,true);
  }

  /**
   * 删除素材
   */
  public static function deleteWxMaterial($media_id){
      \XLog::info("in.media_id=".$media_id, __FILE__, __FUNCTION__, __LINE__);
      $url = 'https://api.weixin.qq.com/cgi-bin/material/del_material?access_token='.self::wxAccessToken();
      return HttpTools::open($url,'POST',json_encode(['media_id'=>$media_id]),false,true);
  }

  /**
   * 获取永久素材
   */
  public static function getWxMaterial($media_id){
      \XLog::info("in.media_id=".$media_id, __FILE__, __FUNCTION__, __LINE__);
      $url = 'https://api.weixin.qq.com/cgi-bin/material/get_material?access_token='.self::wxAccessToken();
      return HttpTools::open($url,'POST',json_encode(['media_id'=>$media_id]),false,true);
  }

  /**
   * 获取临时素材
   */
  public static function getTemporaryWxMaterial($media_id){
      \XLog::info("in.media_id=".$media_id, __FILE__, __FUNCTION__, __LINE__);
      $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.self::wxAccessToken().'&media_id='.$media_id;
      return HttpTools::open($url,'GET',false,false,true);
  }

  /**
   * 发布微信菜单
   */
  public static function pulishMenu($data){
    \XLog::info("in.data=".json_encode($data,JSON_UNESCAPED_UNICODE), __FILE__, __FUNCTION__, __LINE__);
    $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.self::wxAccessToken();
    return HttpTools::open($url,'POST',json_encode(['button'=>$data],JSON_UNESCAPED_UNICODE),false,true);
  }

  /**
   * 获取微信菜单
   */
  public static function getMenu(){
    $url = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token='.self::wxAccessToken();
    return HttpTools::open($url,'GET',false,false,true);
  }


  /**
   * 获取用户信息
   */
  public static function getUserInfo($openid){
//        \XLog::info("in.openid=".$openid, __FILE__, __FUNCTION__, __LINE__);
      $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.self::wxAccessToken()."&openid=".$openid."&lang=en";
      return HttpTools::open($url,'GET',false,false,true);
  }

  /**
   * 网页授权第一步
   * 用户同意授权，获取code
   */
  public static function oauthGetCode($state = 2018){
      \XLog::info("in.", __FILE__, __FUNCTION__, __LINE__);
      $now_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
      $redirect_uri = urlencode($now_url);
      $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.self::$appId.'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_base&state='.$state.'#wechat_redirect';
      header('location:'.$url);
  }

  /**
   * 网页授权第二步
   * 通过code换取网页授权access_token
   * snsapi_base 到此结束
   */
  public static function oauthGetAccessToken($code){
      \XLog::info("in.code=".$code, __FILE__, __FUNCTION__, __LINE__);
      $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.self::$appId.'&secret='.self::$appSecret.'&code='.$code.'&grant_type=authorization_code';
      return HttpTools::open($url,'GET',false,false,true);
  }

  /**
   * 获取客服列表
   */
  public static function getCustomList(){
      \XLog::info("in.", __FILE__, __FUNCTION__, __LINE__);
      $url = 'https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token='.self::wxAccessToken();
      return HttpTools::open($url,'GET',false,false,true);
  }

  /**
   * 删除客服
   */
  public static function deleteCustom($kf_account){
      \XLog::info("in.kf_account=".$kf_account, __FILE__, __FUNCTION__, __LINE__);
      $url = 'https://api.weixin.qq.com/customservice/kfaccount/del?access_token='.self::wxAccessToken().'&kf_account='.$kf_account;
      return HttpTools::open($url,'GET',false,false,true);
  }

  public static function getUserList($params = []){
    \XLog::info("in.params=".json_encode($params), __FILE__, __FUNCTION__, __LINE__);
    $url = isset($params['next_openid']) && !empty($params['next_openid']) ? ('https://api.weixin.qq.com/cgi-bin/user/get?access_token='.self::wxAccessToken().'&next_openid='.$params['next_openid']) : ('https://api.weixin.qq.com/cgi-bin/user/get?access_token='.self::wxAccessToken());
    return HttpTools::open($url,'GET',false,false,true);
  }

  /**
   * 获取用户增减数据
   */
  public static function getusersummary($params){
    \XLog::info("in.params=".json_encode($params), __FILE__, __FUNCTION__, __LINE__);
    $url = 'https://api.weixin.qq.com/datacube/getusersummary?access_token='.self::wxAccessToken();
    return HttpTools::open($url,'POST',json_encode($params,JSON_UNESCAPED_UNICODE),false,true);
  }
  
  /**
   * 获取累计用户数据
   */
  public static function getusercumulate($params){
    \XLog::info("in.params=".json_encode($params), __FILE__, __FUNCTION__, __LINE__);
    $url = 'https://api.weixin.qq.com/datacube/getusercumulate?access_token='.self::wxAccessToken();
    return HttpTools::open($url,'POST',json_encode($params,JSON_UNESCAPED_UNICODE),false,true);
  }
}
