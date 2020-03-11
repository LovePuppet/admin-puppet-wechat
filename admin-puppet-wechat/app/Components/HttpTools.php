<?php
namespace App\Components;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * CURL方法封装
 *
 * @author 郭钊林
 */
class HttpTools {

    const GET = 'GET';
    const POST = 'POST';

    static public $host = '';

    public static function open($uri, $type = 'GET', $post_data = '', $cookie_data = FALSE, $is_https = FALSE, $params = array()) {
//        \XLog::info("in.uri=".$uri.", type=".$type.", post=".json_encode($post_data,JSON_UNESCAPED_UNICODE).", cookie=".json_encode($cookie_data,JSON_UNESCAPED_UNICODE).", is_https=".$is_https.", params=".json_encode($params,JSON_UNESCAPED_UNICODE), __FILE__, __FUNCTION__, __LINE__);
        $user_agent = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; CIBA)";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        //curl_setopt($ch, CURLOPT_TIMEOUT, self::_getIssetData($params, 'timeout', 30));
        //post
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }
        switch ($type) {
            case "GET":
                curl_setopt($ch, CURLOPT_HTTPGET, 1);
                break;
            case "POST":
                curl_setopt($ch, CURLOPT_POST, 1);
                // json格式
                if (isset($post_data['curl_type'])) {
                    if ($post_data['curl_type'] == 'json') {
                        $post_data = (isset($post_data['curl_data']) ? $post_data['curl_data'] : $post_data);
                    }
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json;charset=utf-8"));
                } elseif (!is_array($post_data)) {
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json;charset=utf-8"));
                } elseif (is_array($post_data)) {
                    $o='';
                    foreach ($post_data as $k=>$v)
                    {
                       $o.="$k=".urlencode($v).'&';
                    }
                    $post_data=substr($o,0,-1);
                }
                
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                break;
            case "PUT":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                break;
            default:
                curl_setopt($ch, CURLOPT_HTTPGET, 1);
                break;
        }
        if ($is_https) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        if ($cookie_data) {
            $cookie_data = self::_getCookieData($cookie_data);
            curl_setopt($ch, CURLOPT_COOKIE, $cookie_data);
        }

        //默认ipv4
        $output = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpcode != 200) {
            $output = FALSE;
        }
//        \XLog::info("out.output=".json_encode($output,JSON_UNESCAPED_UNICODE), __FILE__, __FUNCTION__, __LINE__);
        return $output;
    }

    /**
    * 发送数据
    * @param String $url     请求的地址
    * @param Array  $header  自定义的header数据
    * @param Array  $content POST的数据
    * @return String
    */
    public static function tocurl($url, $header, $content){
        $ch = curl_init();
        if(substr($url,0,5)=='https'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($content));
        $response = curl_exec($ch);
        if($error=curl_error($ch)){
            die($error);
        }
        curl_close($ch);
        return $response;
    }
    
    public static function https_post($url,$data){
        \XLog::info("in.url=".$url.", data=".json_encode($data,JSON_UNESCAPED_UNICODE), __FILE__, __FUNCTION__, __LINE__);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
           return 'Errno'.curl_error($curl);
        }
        curl_close($curl);
        \XLog::info("out.result=".json_encode($result,JSON_UNESCAPED_UNICODE), __FILE__, __FUNCTION__, __LINE__);
        return $result;
    }

    private static function _getCookieData($cookie_data) {
        $cookie_arr = array();
        foreach ($cookie_data as $k => $v) {
            $cookie_arr[] = $k . '=' . $v;
        }
        return implode(';', $cookie_arr);
    }

    private static function _getJsonpData($data = FALSE, $jsonp = 'b5mjsonp') {
        $data && $data = str_replace($jsonp . '(', '', $data);
        $data && $data = rtrim($data, ')');
        return $data;
    }
    
    /**
     * 判断数组中的key是否存在
     * @param array $data 数组
     * @param string $key key
     * @param string $defult 默认值
     */
    public static function _getIssetData($data, $key, $defult = '') {
        return (isset($data[$key]) && !empty($data[$key]) ? $data[$key] : $defult);
    }

}
