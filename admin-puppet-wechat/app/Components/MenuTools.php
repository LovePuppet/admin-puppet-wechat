<?php

namespace App\Components;
use Session;
/**
 * 菜单menu工具类
 * @author 郭钊林
 */
class MenuTools {
    public static function isShowMenu($urls){
        $userLimits = (Session::has('userLimits')) ? Session::get('userLimits')[0] : [];
        $result = false;
        if(!empty($urls)){
            foreach ($urls as $url){
                in_array($url,$userLimits) && $result = true;
            }
        }
        return $result;
    }
}

