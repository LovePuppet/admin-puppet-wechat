<?php
namespace App\Components;
/**
 * 微信群发
 * @author 郭钊林
 */
class WxGroupSend{

  /**
   * 上传图文消息内的图片获取URL
   * request media form-data中媒体文件标识，有filename、filelength、content-type等信息
   * respond {  "url":  "http://mmbiz.qpic.cn/mmbiz/gLO17UPS6FS2xsypf378iaNhWacZ1G1UplZYWEYfwvuU6Ont96b1roYs CNFwaRrSaKTPCUdBK9DgEHicsKwWCBRQ/0"}
   */
  public static function uploadImg($params){
    \XLog::info("in.params=".$params, __FILE__, __FUNCTION__, __LINE__);
    $url = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token='.WxTools::wxAccessToken();
    return HttpTools::open($url,'POST',$params,false,true);
  }
  
  /**
   * 上传图文消息素材
   * {
   * request "articles": [ {
   *             "thumb_media_id":"qI6_Ze_6PtV7svjolgs-rN6stStuHIjs9_DidOHaj0Q-mwvBelOXCFZiq2OsIU-p",
   *             "author":"xxx","title":"Happy Day","content_source_url":"www.qq.com","content":"content","digest":"digest",
   *             "show_cover_pic":1},{
   *             "thumb_media_id":"qI6_Ze_6PtV7svjolgs-rN6stStuHIjs9_DidOHaj0Q-mwvBelOXCFZiq2OsIU-p",
   *             "author":"xxx",         "title":"Happy Day",         "content_source_url":"www.qq.com",         "content":"content",         "digest":"digest",
   *             "show_cover_pic":0     }
   *             ] }
   * respond {
   *  "type":"news",
   *  "media_id":"CsEf3ldqkAYJAU6EJeIkStVDSvffUJ54vqbThMgplD-VJXXof6ctX5fI6-aYyUiQ",
   *  "created_at":1391857799
   * }
   */
  public static function uploadNews($params){
    \XLog::info("in.params=".$params, __FILE__, __FUNCTION__, __LINE__);
    $url = 'https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token='.WxTools::wxAccessToken();
    return HttpTools::open($url,'POST',$params,false,true);
  }
  
  /**
   * 根据标签进行群发
   * request {
   *     "filter":{
   *        "is_to_all":false,
   *        "tag_id":2
   *     },
   *     "mpnews":{
   *        "media_id":"123dsdajkasd231jhksad"
   *     },
   *      "msgtype":"mpnews",
   *      "send_ignore_reprint":0
   *  }
   * respond {
   *   "errcode":0,
   *   "errmsg":"send job submission success",
   *   "msg_id":34182, 
   *   "msg_data_id": 206227730
   * }
   */
  public static function sendAll($params){
    \XLog::info("in.params=".$params, __FILE__, __FUNCTION__, __LINE__);
    $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.WxTools::wxAccessToken();
    return HttpTools::open($url,'POST',$params,false,true);
  }
  
  /**
   * 根据OpenID列表群发【订阅号不可用，服务号认证后可用】
   * request {
   *     "touser":[
   *      "OPENID1",
   *      "OPENID2"
   *     ],
   *     "mpnews":{
   *        "media_id":"123dsdajkasd231jhksad"
   *     },
   *      "msgtype":"mpnews"，
   *      "send_ignore_reprint":0
   *  }
   * respond {
   *     "errcode":0,
   *     "errmsg":"send job submission success",
   *     "msg_id":34182, 
   *     "msg_data_id": 206227730
   *  }
   */
  public static function send($params){
    \XLog::info("in.params=".$params, __FILE__, __FUNCTION__, __LINE__);
    $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.WxTools::wxAccessToken();
    return HttpTools::open($url,'POST',$params,false,true);
  }
  
  /**
   * 预览接口【订阅号与服务号认证后均可用】
   * request {
   *     "touser":"OPENID", 
   *     "mpnews":{              
   *              "media_id":"123dsdajkasd231jhksad"               
   *               },
   *     "msgtype":"mpnews" 
   *  }
   * respond {
   *   "errcode":0,
   *   "errmsg":"preview success",
   *   "msg_id":34182
   * }
   */
  public static function preview($params){
    \XLog::info("in.params=".$params, __FILE__, __FUNCTION__, __LINE__);
    $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token='.WxTools::wxAccessToken();
    return HttpTools::open($url,'POST',$params,false,true);
  }
}