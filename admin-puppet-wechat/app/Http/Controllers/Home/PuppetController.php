<?php
namespace App\Http\Controllers\Home;
use App\Http\Controllers\Controller;
class PuppetController extends Controller{
    /**
     * 获取access_token
     */
    public function getWxAccessToken(){
        echo \App\Components\WxTools::wxAccessToken();
        exit;
    }
    
    /**
     * 刷新access_token
     */
    public function refreshWxAccessToken(){
        echo \App\Components\WxTools::wxAccessToken(true);
        exit;
    }
}
