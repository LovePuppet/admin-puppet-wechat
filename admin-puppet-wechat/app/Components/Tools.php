<?php
namespace App\Components;
use Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Log;
/**
 * 工具类
 *
 * @author 郭钊林
 */
class Tools {
    private static $allow_url = [
        '/admin',
        '/admin/update/password'
    ];
    
    /**
     * 生成验证码6位随机数
     */
    public static function getVerCode() {
        return rand(100000, 999999);
    }
    
    /**
     * 将对象或者数组里包含的对象全部转成数组
     * @param $array  array or obj
     * @return array
     */
    public static function objectToArray($array) {
        if(is_object($array)) {
            $array = (array)$array;
        }
        if(is_array($array)) {
            foreach($array as $key => $value) {
                $array[$key] = self::objectToArray($value);
            }
        }
        return $array;
    }
    
    /**
     * 验证手机号码的格式是否正确
     */
    public static function verifyMobile($mobile){
        return preg_match("/^1[34578]\d{9}$/",$mobile);
    }
    
    /**
     * 验证邮箱的格式是否正确
     */
    public static function verifyEmail($email){
        return preg_match("/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/",$email);
    }

        /**
     * 后台管理员登录密码加密
     * $password  密码
     * $v_password 需要验证的密码
     */
    public static function passwordEncryption($password,$v_password = ''){
        $result = true;
        if(empty($v_password)){
            $result = md5(env('ADMIN_PASSWORD','puppet').md5($password));
        }else{
            (self::passwordEncryption($v_password) != $password) && $result = false;
        }
        return $result;
    }
    
    /**
     * 所有权限数据
     */
    public static function adminLimits($refresh = false){
        $result = Cache::get('adminLimits');
        if(!empty($result)){
            if($refresh){
                Cache::forget('adminLimits');
                self::adminLimits();
            }
            $result = unserialize($result);
        }else{
            $oAdminLimit = new \App\Models\AdminModels\AdminLimit;
            $result = $oAdminLimit->getAllData();
            $expiresAt = Carbon::now()->addDays(7);//缓存7天
            Cache::put('adminLimitsV2', serialize($result),$expiresAt);
        }
        return $result;
    }
    
    /**
     * 所有角色数据
     */
    public static function adminRoles($refresh = false){
        $result = Cache::get('adminRoles');
        if(!empty($result)){
            if($refresh){
                Cache::forget('adminRoles');
                self::adminRoles();
            }
            $result = unserialize($result);
        }else{
            $oAdminRole = new \App\Models\AdminModels\AdminRole;
            $result = $oAdminRole->getAllData();
            $expiresAt = Carbon::now()->addDays(7);//缓存7天
            Cache::put('adminRolesV2', serialize($result),$expiresAt);
        }
        return $result;
    }
    
    /**
     * 根据权限limit_ids
     * 获取权限名称数组，或者权限url数组
     */
    public static function getAdminLimitsNameOrUrl($limit_ids,$val = 'limit_name'){
        $adminLimits = self::adminLimits();
        $result = [];
        foreach ($adminLimits as $limit){
            if(in_array($limit['admin_limit_id'], $limit_ids)){
                $result[] = $limit[$val];
            }
        }
        return $result;
    }
    
    /**
     * 验证一个url是否有权限访问
     */
    public static function urlLimit($url,$request){
        $result = false;
        if(!empty($url)){
            $url = preg_replace('/\?(\S+)/', '', $url);//正则替换掉url参数 ?id=...&name=...
            $url = preg_replace('/\/\d{0,}$/','',$url);//正则替换掉/1
            $userLimits = [];
            if($request->session()->has('userLimits')){
                $session_limits = $request->session()->get('userLimits');
                $userLimits = !empty($session_limits) ? $session_limits[0] : [];
            }
            if(in_array($url, self::$allow_url) || in_array($url,$userLimits)){
                $result = true;
            }
        }
        return $result;
    }
    
    /**
     * 获取随机字符串
     * @param type $length 字符串长度
     * @return string 字符串
     */
    public static function getRandChar($length){
        $str = null;
        $strPol = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $max = strlen($strPol)-1;
        for($i=0;$i<$length;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $str;
    }
    
    public static function token($user_id){
        return md5(time().$user_id. rand(10000, 99999));
    }

    /**
     * 生成token加密串
     */
    public static function crcToken($token){
        $result = sprintf("%u",crc32($token));
        $show = '';
        while($result  >0){
            $s = $result % 62;
            if($s > 35){
                $s=chr($s+61);
            }elseif($s>9 && $s<=35){
                $s=chr($s+55);
            }
            $show .= $s;
            $result = floor($result / 62);
        }
        return $show;
    }
    
    /**
     * 替换内容中的用户昵称
     * $add 是否需要判断用户是否存在，不存获取数据存入用户表
     */
    public static function replaceNickname($content,$openid,$add = false){
        $oUser = new \App\Models\UserModels\User();
        if(strpos($content,'${userName}') !== false){
            $nickname = $oUser->getNickname($openid);
            $content = str_replace('${userName}',$nickname,$content);
        }else{
            if($add){
                $oUser->getNickname($openid);
            }
        }
        return $content;
    }
    
    /**
     * subscribe_scene
     * 返回用户关注的渠道来源
     */
    public static function subscribeScene($subscribe_scene){
        $result = '';
        switch ($subscribe_scene){
            case 'ADD_SCENE_SEARCH':
                $result = '公众号搜索';
                break;
            case 'ADD_SCENE_ACCOUNT_MIGRATION':
                $result = '公众号迁移';
                break;
            case 'ADD_SCENE_PROFILE_CARD':
                $result = '名片分享';
                break;
            case 'ADD_SCENE_QR_CODE':
                $result = '扫描二维码';
                break;
            case 'ADD_SCENEP_ROFILE_LINK':
                $result = '图文页内名称点击';
                break;
            case 'ADD_SCENE_PROFILE_ITEM':
                $result = '图文页右上角菜单';
                break;
            case 'ADD_SCENE_PAID':
                $result = '支付后关注';
                break;
            case 'ADD_SCENE_OTHERS':
                $result = '其他';
                break;
            default :
                $result = '';
                break;
        }
        return $result;
    }

    /**
     * 获取微信
     */
    public static function wxMsgRecord($openid,$data = []){
//        \XLog::info("in.openid=".$openid.", data=".json_encode($data), __FILE__, __FUNCTION__, __LINE__);
        $result = [];
        $key = self::crcToken($openid);
        if(!empty($data)){
//            $expiresAt = Carbon::now()->addMinutes(30);//缓存30分钟
            $expiresAt = Carbon::now()->addDays(1);//缓存1天
            $o_data = Cache::get($key);
            if(!empty($o_data)){
                $o_data = unserialize($o_data);
                if(isset($data['fid'])){
                    $o_data['fid'] = $data['fid'];
                }
                if(isset($data['is_lang'])){
                    $o_data['is_lang'] = $data['is_lang'];
                }
                if(isset($data['lang'])){
                    $o_data['lang'] = $data['lang'];
                }
                if(isset($data['is_customer_type'])){
                    $o_data['is_customer_type'] = $data['is_customer_type'];
                }
                if(isset($data['is_end'])){
                    $o_data['is_end'] = $data['is_end'];
                }
                if(isset($data['userinfo'])){
                    $o_data['userinfo'] = $data['userinfo'];
                }
                if(isset($data['timing'])){
                    $o_data['timing'] = $data['timing'];
                }
            }else{
                $o_data = $data;
            }
            Cache::put($key, serialize($o_data),$expiresAt);
            $result = $o_data;
        }else{
            $value = Cache::get($key);
            if(!empty($value)){
                $result = unserialize($value);
            }
        }
//        \XLog::info("out.result=".json_encode($result), __FILE__, __FUNCTION__, __LINE__);
        return $result;
    }
    
    /**
     * 清除缓存
     */
    public static function cleanWxMsgRecord($openid){
        $key = self::crcToken($openid);
        Cache::forget($key);
        return true;
    }

        /**
     * 获取素材 text  image news html
     * @param type $type
     * @param type $content
     */
    public static function getMaterialHtml($type,$content){
      $result = '';
      if(!empty($type) && !empty($content)){
        switch ($type){
          case 'text':
            $oText = new \App\Models\MaterialModels\Text();
            $data = $oText->db_get(['crc_token'=>['=',$content],'status'=>['=',1]]);
            if(!empty($data)){
              $result = '<textarea class="form-control" rows="6" id="text_content_val">'.$data['content'].'</textarea>';
            }
            break;
          case 'image':
            $oImage = new \App\Models\MaterialModels\Image();
            $data = $oImage->db_get(['media_id'=>['=',$content],'status'=>['=',1]]);
            if(!empty($data)){
              $result = '<li class="item"><div class="content"><div class="avatar" style="background-image: url('. env('IMAGE_DOMIAN').$data['url'].')"></div></div></li>';
            }
            break;
          case 'news':
            $oNews = new \App\Models\MaterialModels\News();
            $data = $oNews->db_get(['media_id'=>['=',$content],'status'=>['=',1]]);
            if(!empty($data)){
              $urls = json_decode($data['url'],true);
              $titles = json_decode($data['title'],true);
              $result = '<li class="item"><div class="content"><div class="avatar" style="background-image: url('.env('IMAGE_DOMIAN').$urls[0].')"><p class="title">'.$titles[0].'</p></div></div>';
              if(count($urls) > 1){
                  foreach ($urls as $key => $val){
                      $result .= '<div class="line"><p class="txt">'.$titles[$key].'</p><div class="avatar" style="background-image: url('.env('IMAGE_DOMIAN').$val.')"></div></div>';
                  }
              }
              $result .= "</li>";
            }
            break;
          case 'video':
            $oVideo = new \App\Models\MaterialModels\video();
            $data = $oVideo->db_get(['media_id'=>['=',$content],'status'=>['=',1]]);
            if(!empty($data)){
              $result = '<li class="item"><div class="content"><p>'.$data['title'].'</p></div></li>';
            }
            break;
        }
      }
      return $result;
    }
  
  /**
   * 语言名称
   */
  public static function langName($lang){
    $result = '';
    switch ($lang){
      case 0:
        $result = 'All';
        break;
      case 1:
        $result = 'English';
        break;
      case 2:
        $result = 'Chinese';
        break;
    }
    return $result;
  }
  
  /**
   * 客户类型名称
   */
  public static function customerTypeName($customer_type){
    $result = '';
    switch ($customer_type){
      case 0:
        $result = 'All';
        break;
      case 1:
        $result = 'Agency';
        break;
      case 2:
        $result = 'Brand';
        break;
    }
    return $result;
  }
  
  /**
   * 素材类型名称
   */
  public static function materialTypeName($material_type){
    $result = '';
    switch ($material_type){
      case 1:
        $result = '文本';
        break;
      case 2:
        $result = '图片';
        break;
      case 3:
        $result = '图文';
        break;
    }
    return $result;
  }
  
  /**
   * 中英文切换
   * 默认英文
   */
  public static function sLang($en_content,$cn_content){
    $result = $en_content;
    $lang = Cookie::get('puppet_lang');
    if($lang == 2){
      $result = $cn_content;
    }
    return $result;
  }
}