<?php
namespace App\Components;
/**
 * 微信二维码
 * @author 郭钊林
 */
class WxQrcode{
    /**
     * 创建二维码ticket
     * request {"expire_seconds": 604800, "action_name": "QR_SCENE/QR_STR_SCENE[临时整型参数/临时字符串参数]", "action_info": {"scene": {"scene_str": "test"}}}
     * request {"action_name": "QR_LIMIT_SCENE/QR_LIMIT_STR_SCENE[永久整型参数/永久字符串参数]", "action_info": {"scene": {"scene_str": "test"}}}
     * respond {"ticket":"gQH47joAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL2taZ2Z3TVRtNzJXV1Brb3ZhYmJJAAIEZ23sUwMEmm3sUw==","expire_seconds":60,"url":"http://weixin.qq.com/q/kZgfwMTm72WWPkovabbI"}
     */
    public static function createQrcode($params){
        \XLog::info("in.params=".$params, __FILE__, __FUNCTION__, __LINE__);
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.WxTools::wxAccessToken();
        return HttpTools::open($url,'POST',$params,false,true);
    }
    
    /**
     * 通过ticket
     * HTTP GET请求（请使用https协议）https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=TICKET
     *request  提醒：TICKET记得进行UrlEncode
     * respond HTTP头（示例）如下：
     *   Accept-Ranges:bytes
     *   Cache-control:max-age=604800
     *   Connection:keep-alive
     *   Content-Length:28026
     *   Content-Type:image/jpg
     *   Date:Wed, 16 Oct 2013 06:37:10 GMT
     *   Expires:Wed, 23 Oct 2013 14:37:10 +0800
     *   Server:nginx/1.4.1
     */
    public static function showqrcode($ticket){
        \XLog::info("in.ticket=".$ticket, __FILE__, __FUNCTION__, __LINE__);
        $url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket);
        return HttpTools::open($url,'GET',false,false,true);
    }
}
