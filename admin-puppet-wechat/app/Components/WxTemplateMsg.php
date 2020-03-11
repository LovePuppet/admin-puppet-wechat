<?php

namespace app\models;
use Yii;
use app\components\Tools;
use app\components\HttpTools;
/**
 * 微信模板消息类
 * @author 郭钊林
 */
class WxTemplateMsg{
    
    public static function send($arr,$fun = 'register'){
        $accessToken = WechatAccessToken::getAccessToken();
        $url = Yii::$app->params['wechat_params']['wechat_urls']['module_url'].'?access_token='.$accessToken;
        $function = $fun.'TemplateMsg';
        $result = HttpTools::open($url,'POST',self::$function($arr,$fun),false,true);
        Log::printLog('/logs/guozl.log','$result:'.$result,4);
        $res = json_decode($result,TRUE);
        return $res;
    }
    
    /**
     * 会员注册通知
     * 微信消息模板
     * 
     */
    public static function registerTemplateMsg($arr,$fun){
        $data = [
                "first" => [
                    "value" => Tools::isSetData($arr, 'first_value'),
                    "color" => Tools::isSetData($arr, 'first_color','#173177')
                ],
                "keyword1" => [
                    "value" => Tools::isSetData($arr, 'keyword1_value'),
                    "color" => Tools::isSetData($arr, 'keyword1_color','#173177')
                ],
                "keyword2" => [
                    "value" => Tools::isSetData($arr, 'keyword2_value'),
                    "color" => Tools::isSetData($arr, 'keyword2_color','#173177')
                ],
                "keyword3" => [
                    "value" => Tools::isSetData($arr, 'keyword3_value'),
                    "color" => Tools::isSetData($arr, 'keyword3_color','#173177')
                ],
                "remark" => [
                    "value" => Tools::isSetData($arr, 'remark_value'),
                    "color" => Tools::isSetData($arr, 'remark_color','#173177')
                ],
            ];
        return $this->templateMsg($arr, $fun, $data);
    }
    
    /**
     * 需求状态变更
     * 微信消息模板
     */
    public static function demandTemplateMsg($arr,$fun){
        $data = [
                "first" => [
                    "value" => Tools::isSetData($arr, 'first_value'),
                    "color" => Tools::isSetData($arr, 'first_color','#173177')
                ],
                "keyword1" => [
                    "value" => Tools::isSetData($arr, 'keyword1_value'),
                    "color" => Tools::isSetData($arr, 'keyword1_color','#173177')
                ],
                "keyword2" => [
                    "value" => Tools::isSetData($arr, 'keyword2_value'),
                    "color" => Tools::isSetData($arr, 'keyword2_color','#173177')
                ],
                "remark" => [
                    "value" => Tools::isSetData($arr, 'remark_value'),
                    "color" => Tools::isSetData($arr, 'remark_color','#173177')
                ],
            ];
        return self::templateMsg($arr, $fun, $data);
    }
    
    /**
     * 需求状态变更
     * 微信消息模板
     */
    public static function bookTemplateMsg($arr,$fun){
        $data = [
                "first" => [
                    "value" => Tools::isSetData($arr, 'first_value'),
                    "color" => Tools::isSetData($arr, 'first_color','#173177')
                ],
                "keyword1" => [
                    "value" => Tools::isSetData($arr, 'keyword1_value'),
                    "color" => Tools::isSetData($arr, 'keyword1_color','#173177')
                ],
                "keyword2" => [
                    "value" => Tools::isSetData($arr, 'keyword2_value'),
                    "color" => Tools::isSetData($arr, 'keyword2_color','#173177')
                ],
                "keyword3" => [
                    "value" => Tools::isSetData($arr, 'keyword3_value'),
                    "color" => Tools::isSetData($arr, 'keyword3_color','#173177')
                ],
                "remark" => [
                    "value" => Tools::isSetData($arr, 'remark_value'),
                    "color" => Tools::isSetData($arr, 'remark_color','#173177')
                ],
            ];
        return self::templateMsg($arr, $fun, $data);
    }
    
    private static function templateMsg($arr,$fun,$data){
        $res = [
            "touser" => Tools::isSetData($arr, 'openid'),
            "template_id" => Yii::$app->params['wechat_params']['wechat_module_ids'][$fun.'_notice'],
            "url" => Tools::isSetData($arr, 'url'),
            "topcolor" => Tools::isSetData($arr, 'topcolor','#FF0000'),
            "data" => $data,
        ];
        return json_encode($res);
    }
}
