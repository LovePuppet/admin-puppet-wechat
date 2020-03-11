<?php
namespace App\Models\RobotModels;
use App\Models\ParentModel;
use DB;
use App\Components\Tools;
use App\Components\WxTools;
use Log;
/**
 * 微信用户model
 * @author puppet
 */
class Robot extends ParentModel{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'robot';

    /**
     * DB::直接操作的数据库表名
     * @var string
     */
    protected $db_table = 'pt_robot';

    /**
     * 主键 默认id。
     *
     * @var string
     */
    protected $primaryKey = 'robot_id';
    
    /**
     * 指定是否模型应该被戳记时间。
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * 翻页展示所有信息
     */
    public function getPageData($params){
        $res = false;
        $limit_num = (isset($params['limit']) && intval($params['limit'])>0 && intval($params['limit']) <= 100) ? $params['limit'] : 10;
        $limit_sta = 0;
        $page = 1;
        if (isset($params['page']) && intval($params['page']) > 0) {
            $limit_sta = intval($limit_num * intval($params['page'] - 1));
            $page = intval($params['page']);
        }
        $cou_sql = "SELECT count(*) as count FROM {$this->db_table} WHERE `status` = ?";
        $sql = "SELECT robot_id,fid,keyword,content,create_time FROM {$this->db_table} WHERE `status` = ?";
        $values = [1];
        $fid = 0;
        if(isset($params['fid']) && intval($params['fid']) > 0){
            $fid = intval($params['fid']);
        }
        $cou_sql .= " AND fid = {$fid}";
        $sql .= " AND fid = {$fid}";
        $count = DB::select($cou_sql,$values);
        $count = isset($count[0]['count']) ? $count[0]['count'] : 0;
        if(isset($params['order']) && !empty($params['order'])){
            $order_db = ['robot_id'];
            $sql .= " ORDER BY ";
            foreach ($params['order'] as $order){
                $result = isset($order_db[$order['column']]) ? $order_db[$order['column']] : '';
                !empty($result) && $sql .= $result.' '.$order['dir'].',';
            }
            $sql = substr($sql,0,-1);
        }
        $sql .= " LIMIT $limit_sta,$limit_num";
        $order_list = DB::select($sql,$values);
        $res['data'] = $order_list;
        $res['total'] = $count;
        //总条数记录
        return $res;
    }
    
    /**
     * 返回所有关键词
     * @param type $fid
     * @param type $force   是否强制检索全部数据
     */
    public function getAllKeyword($fid = 0){
        $sql = "SELECT robot_id,fid,keyword,content,is_end,is_customer_type,userinfo,is_lang FROM {$this->db_table} WHERE `status` = ? AND fid = ?";
        $values = [1,$fid];
        return DB::select($sql,$values);
    }


    public function getReplyContent($oPost){
        \XLog::info("in.oPost=".json_encode($oPost,JSON_UNESCAPED_UNICODE), __FILE__, __FUNCTION__, __LINE__);
        $cache = Tools::wxMsgRecord($oPost->FromUserName);
        \XLog::info("in.cache=".json_encode($cache), __FILE__, __FUNCTION__, __LINE__);
//        if(isset($cache['timing']) && $cache['timing'] == 1){ //定时消息回复
//            $lang = isset($cache['lang']) ? $cache['lang'] : 1;
//            if((strtolower($oPost->Content) == 'a')){
//                $msg = ['touser'=>(string)$oPost->FromUserName,'msgtype'=>'text','text'=>['content'=>"VWx0h"]];
//                if($lang == 1){
//                    $msg['customservice'] = ['kf_account'=>'kf2002@sprosoft'];
//                }else{
//                    $msg['customservice'] = ['kf_account'=>'kf2001@sprosoft'];
//                }
//                \App\Components\WxSend::timingMsgSend($oPost->FromUserName,[$msg]);
//                Tools::wxMsgRecord($oPost->FromUserName,['timing'=>0,'is_end'=>1]);
//            }else if((strtolower($oPost->Content) == 'b')){
//                $msg = [];
//                if($lang == 1){
//                    $msg = ['touser'=>(string)$oPost->FromUserName,'msgtype'=>'text','text'=>['content'=>"1CxSL4"],'customservice'=>['kf_account'=>'kf2002@sprosoft']];
//                }else{
//                    $msg = ['touser'=>(string)$oPost->FromUserName,'msgtype'=>'text','text'=>['content'=>"GaiI11"],'customservice'=>['kf_account'=>'kf2001@sprosoft']];
//                }
//                \App\Components\WxSend::timingMsgSend($oPost->FromUserName,[$msg]);
//                Tools::wxMsgRecord($oPost->FromUserName,['timing'=>0,'is_end'=>1]);
//            }else{
//                $noTrigger = $this->getNoTrigger($oPost->FromUserName);
//                foreach ($noTrigger as $noTri){
//                    WxTools::transmitCustom($noTri);
//                    sleep(1);
//                    continue;
//                }
//            }
//        }else{
            $fid = (!empty($cache) && isset($cache['fid'])) ? intval($cache['fid']) : 0;
            $data = $this->getAllKeyword($fid);
            $content = '';
            $is_end = 0;
            $is_customer_type = 0;
            $userinfo = 0;
            if(!empty($data)){
                foreach ($data as $val){
                    if(empty($val['keyword'])){
                        $content = $val['content'];
                        $fid = $val['robot_id'];
                        $is_end = $val['is_end'];
                        $is_customer_type = $val['is_customer_type'];
                        $userinfo = $val['userinfo'];
                        break;
                    }else{
                        $keywords = json_decode($val['keyword'],true);
                        $lower = strtolower($oPost->Content);
                        $upper = strtoupper($oPost->Content);
                        if(in_array($lower, $keywords) || in_array($upper, $keywords)){
                            $content = $val['content'];
                            $fid = $val['robot_id'];
                            $is_end = $val['is_end'];
                            $is_customer_type = $val['is_customer_type'];
                            $userinfo = $val['userinfo'];
                            break;
                        }
                    }
                }
                if(!empty($content)){
                    if(isset($cache['is_lang']) && $cache['is_lang'] == 1){//如果要收集语言
                        $oThirdUser = new \App\Models\UserModels\ThirdUser();
                        $thirdUserData = $oThirdUser->db_get(['openid'=>['=',$oPost->FromUserName]]);
                        if(!empty($thirdUserData)){
                            $oUser = new \App\Models\UserModels\User();
                            $lang = 0;
                            if((strtolower($oPost->Content) == 'a' || strtolower($oPost->Content) == 1)){
                                $lang = 1;
                            }else if((strtolower($oPost->Content) == 'b' || strtolower($oPost->Content) == 2)){
                                $lang = 2;
                            }
                            $oUser->db_update('',$thirdUserData['user_id'],['lang'=>$lang]);
                            Tools::wxMsgRecord($oPost->FromUserName,['is_lang'=>0,'lang'=>$lang]);
                        }
                    }
                    if(isset($cache['is_customer_type']) && $cache['is_customer_type'] == 1){//如果要收集客户类型
                        $oThirdUser = new \App\Models\UserModels\ThirdUser();
                        $thirdUserData = $oThirdUser->db_get(['openid'=>['=',$oPost->FromUserName]]);
                        if(!empty($thirdUserData)){
                            $oUser = new \App\Models\UserModels\User();
                            $customer_type = 0;
                            if((strtolower($oPost->Content) == 'a')){
                                $customer_type = 1;
                            }else if((strtolower($oPost->Content) == 'b')){
                                $customer_type = 2;
                            }
                            $oUser->db_update('',$thirdUserData['user_id'],['customer_type'=>$customer_type]);
                            Tools::wxMsgRecord($oPost->FromUserName,['is_customer_type'=>0]);
                        }
                    }
                    if(isset($cache['userinfo']) && $cache['userinfo'] == 1){//如果要收集客户姓名+邮箱
                        $oThirdUser = new \App\Models\UserModels\ThirdUser();
                        $thirdUserData = $oThirdUser->db_get(['openid'=>['=',$oPost->FromUserName]]);
                        if(!empty($thirdUserData)){
                            if(strpos($oPost->Content,'+') !== false){
                                $userinfo_arr = explode('+', $oPost->Content);
                                $userData['real_name'] = trim($userinfo_arr[0]);
                                $userData['email'] = trim($userinfo_arr[1]);
                                $oUser = new \App\Models\UserModels\User();
                                $oUser->db_update('',$thirdUserData['user_id'],$userData);
                                Tools::wxMsgRecord($oPost->FromUserName,['userinfo'=>0]);
                            }else{
                                if(!Tools::verifyEmail($oPost->Content)){
                                    $this->errorEmail($oPost->FromUserName);
                                    return true;
                                }else{
                                    $userData['email'] = trim($oPost->Content);
                                    $oUser = new \App\Models\UserModels\User();
                                    $oUser->db_update('',$thirdUserData['user_id'],$userData);
                                    Tools::wxMsgRecord($oPost->FromUserName,['userinfo'=>0]);
                                }
                            }
                        }
                    }
                    \App\Components\WxSend::multipleMsgSend($oPost,$content,$fid,$is_customer_type,$is_end,$userinfo);
                }else{  //不在关键词之内    上一条消息重发
                    if(isset($cache['is_end']) && $cache['is_end'] == 1){
                        $suddenlyReply = $this->suddenlyReply($oPost->FromUserName);
                        foreach ($suddenlyReply as $reply){
                            WxTools::transmitCustom($reply);
                            sleep(1);
                            continue;
                        }
                    }else{
                        $noTrigger = $this->getNoTrigger($oPost->FromUserName);
                        foreach ($noTrigger as $noTri){
                            WxTools::transmitCustom($noTri);
                            sleep(1);
                            continue;
                        }
                        if($fid > 0){
                            $data = $this->db_get(['robot_id'=>['=',$fid]]);
                            if(!empty($data)){
                                \App\Components\WxSend::multipleMsgSend($oPost,$data['content']);
                            }
                        }else{  //发送关注消息

                        }
                    }
                }
            }else{
                $suddenlyReply = $this->suddenlyReply($oPost->FromUserName);
                foreach ($suddenlyReply as $reply){
                    WxTools::transmitCustom($reply);
                    sleep(1);
                    continue;
                }
            }
//        }
        return true;
    }
    
    /**
     * 根据用户选择的语言，回复没有触发关键词的消息
     */
    public function getNoTrigger($openid){
        \XLog::info("in.openid=".$openid, __FILE__, __FUNCTION__, __LINE__);
        $result = [];
        $msg_default = ['touser'=>(string)$openid,'msgtype'=>'image','image'=>['media_id'=>"jCDZhRx-dnrMUjZGR8ApoHlidKAqZ1-prgXTIRpjuos"]];
        $msg_en = ['touser'=>(string)$openid,'msgtype'=>'text','text'=>['content'=>"Sorry I can't understand. Could you please try it again?"]];
        $msg_cn = ['touser'=>(string)$openid,'msgtype'=>'text','text'=>['content'=>'我真的看不懂。你能不能再试一次？']];
        $lang = 0;
        $cache = Tools::wxMsgRecord($openid);
        if(isset($cache['lang'])){
            $lang = $cache['lang'];
        }else{
            $oThirdUser = new \App\Models\UserModels\ThirdUser();
            $thirdUserData = $oThirdUser->db_get(['openid'=>['=',$openid]]);
            if($thirdUserData){
                $oUser = new \App\Models\UserModels\User();
                $user_data = $oUser->db_get(['user_id'=>['=',$thirdUserData['user_id']]]);
                !empty($user_data) && $lang = $user_data['lang'];
            }
        }
        switch ($lang){     //语言
            case 1:
                $msg_default['customservice'] = ['kf_account'=>'kf2002@sprosoft'];
                $msg_en['customservice'] = ['kf_account'=>'kf2002@sprosoft'];
                $result[] = $msg_default;
                $result[] = $msg_en;
                break;
            case 2:
                $msg_default['customservice'] = ['kf_account'=>'kf2001@sprosoft'];
                $msg_cn['customservice'] = ['kf_account'=>'kf2001@sprosoft'];
                $result[] = $msg_default;
                $result[] = $msg_cn;
                break;
            default :
                $result[] = $msg_default;
                $result[] = $msg_en;
                $result[] = $msg_cn;
                break;
        }
        \XLog::info("out.result=".json_encode($result,JSON_UNESCAPED_UNICODE), __FILE__, __FUNCTION__, __LINE__);
        return $result;
    }
    
    /**
     * 根据用户缓存失效后，突然再回复消息
     */
    public function suddenlyReply($openid){
//        \XLog::info("in.openid=".$openid, __FILE__, __FUNCTION__, __LINE__);
        $result = [];
        $msg_cn_1 = ['touser'=>(string)$openid,'msgtype'=>'text','text'=>['content'=>"请问您需要怎样的帮助？"]];
        $msg_cn_2 = ['touser'=>(string)$openid,'msgtype'=>'text','text'=>['content'=>'<a href="http://wechat.weiniweixiao.com/wechat/oauth">点击此处告诉我们</a>']];
        $msg_en_1 = ['touser'=>(string)$openid,'msgtype'=>'text','text'=>['content'=>'okok, how can we help you?']];
        $msg_en_2 = ['touser'=>(string)$openid,'msgtype'=>'text','text'=>['content'=>'<a href="http://wechat.weiniweixiao.com/wechat/oauth">Click here to let us know</a>']];
        $lang = 0;
        $cache = Tools::wxMsgRecord($openid);
        if(isset($cache['lang'])){
            $lang = $cache['lang'];
        }else{
            $oThirdUser = new \App\Models\UserModels\ThirdUser();
            $thirdUserData = $oThirdUser->db_get(['openid'=>['=',$openid]]);
            if($thirdUserData){
                $oUser = new \App\Models\UserModels\User();
                $user_data = $oUser->db_get(['user_id'=>['=',$thirdUserData['user_id']]]);
                !empty($user_data) && $lang = $user_data['lang'];
            }
        }
        switch ($lang){     //语言
            case 1:
                $msg_en_1['customservice'] = ['kf_account'=>'kf2002@sprosoft'];
                $msg_en_2['customservice'] = ['kf_account'=>'kf2002@sprosoft'];
                $result[] = $msg_en_1;
                $result[] = $msg_en_2;
                break;
            case 2:
                $msg_cn_1['customservice'] = ['kf_account'=>'kf2001@sprosoft'];
                $msg_cn_2['customservice'] = ['kf_account'=>'kf2001@sprosoft'];
                $result[] = $msg_cn_1;
                $result[] = $msg_cn_2;
                break;
            default :
                $result[] = $msg_en_1;
                $result[] = $msg_cn_1;
                $result[] = $msg_en_2;
                $result[] = $msg_cn_2;
                break;
        }
//        \XLog::info("out.result=".json_encode($result,JSON_UNESCAPED_UNICODE), __FILE__, __FUNCTION__, __LINE__);
        return $result;
    }
    
    /**
     * 定时发送
     */
    public function timingSend($openid){
//        \XLog::info("in.openid=".$openid, __FILE__, __FUNCTION__, __LINE__);
        $result = [];
        $msg_cn_1 = ['touser'=>(string)$openid,'msgtype'=>'text','text'=>['content'=>"p4eTN1"]];
        $msg_cn_2 = ['touser'=>(string)$openid,'msgtype'=>'text','text'=>['content'=>'pfLV32']];
        $msg_cn_3 = ['touser'=>(string)$openid,'msgtype'=>'text','text'=>['content'=>'QzKVr3']];
        $msg_en_1 = ['touser'=>(string)$openid,'msgtype'=>'text','text'=>['content'=>"G0NEa1"]];
        $msg_en_2 = ['touser'=>(string)$openid,'msgtype'=>'text','text'=>['content'=>'6cTQx2']];
        $msg_en_3 = ['touser'=>(string)$openid,'msgtype'=>'text','text'=>['content'=>'wqr5b3']];
        $msg_default = ['touser'=>(string)$openid,'msgtype'=>'text','text'=>['content'=>"VWx0h"]];
        $lang = 0;
        $cache = Tools::wxMsgRecord($openid);
        if(isset($cache['lang'])){
            $lang = $cache['lang'];
        }else{
            $oThirdUser = new \App\Models\UserModels\ThirdUser();
            $thirdUserData = $oThirdUser->db_get(['openid'=>['=',$openid]]);
            if($thirdUserData){
                $oUser = new \App\Models\UserModels\User();
                $user_data = $oUser->db_get(['user_id'=>['=',$thirdUserData['user_id']]]);
                !empty($user_data) && $lang = $user_data['lang'];
            }
        }
        switch ($lang){     //语言
            case 1:
                $msg_en_1['customservice'] = ['kf_account'=>'kf2002@sprosoft'];
                $msg_en_2['customservice'] = ['kf_account'=>'kf2002@sprosoft'];
                $msg_en_3['customservice'] = ['kf_account'=>'kf2002@sprosoft'];
                $msg_default['customservice'] = ['kf_account'=>'kf2002@sprosoft'];
                $result[] = $msg_en_1;
                $result[] = $msg_en_2;
                $result[] = $msg_en_3;
                $result[] = $msg_default;
                break;
            case 2:
                $msg_cn_1['customservice'] = ['kf_account'=>'kf2001@sprosoft'];
                $msg_cn_2['customservice'] = ['kf_account'=>'kf2001@sprosoft'];
                $msg_cn_3['customservice'] = ['kf_account'=>'kf2001@sprosoft'];
                $msg_default['customservice'] = ['kf_account'=>'kf2001@sprosoft'];
                $result[] = $msg_cn_1;
                $result[] = $msg_cn_2;
                $result[] = $msg_cn_3;
                $result[] = $msg_default;
                break;
            default :
                $result[] = $msg_en_1;
                $result[] = $msg_cn_1;
                $result[] = $msg_en_2;
                $result[] = $msg_cn_2;
                $result[] = $msg_en_3;
                $result[] = $msg_cn_3;
                $result[] = $msg_default;
                break;
        }
//        \XLog::info("out.result=".json_encode($result,JSON_UNESCAPED_UNICODE), __FILE__, __FUNCTION__, __LINE__);
        \App\Components\WxSend::timingMsgSend($openid,$result);
        Tools::wxMsgRecord($openid,['timing'=>0,'is_end'=>1]);//设置定时开
        return true;
    }
    
    /**
     * 1min钟发送一次消息
     */
    public function oneMinTimingSend($openid){
        $cache = Tools::wxMsgRecord($openid);
        $result = [];
        $lang = 0;
        if(isset($cache['lang'])){
            $lang = $cache['lang'];
        }else{
            $oThirdUser = new \App\Models\UserModels\ThirdUser();
            $thirdUserData = $oThirdUser->db_get(['openid'=>['=',$openid]]);
            if($thirdUserData){
                $oUser = new \App\Models\UserModels\User();
                $user_data = $oUser->db_get(['user_id'=>['=',$thirdUserData['user_id']]]);
                !empty($user_data) && $lang = $user_data['lang'];
            }
        }
//        if(isset($cache['timing']) && $cache['timing'] == 1){
//            $msg_default = ['touser'=>(string)$openid,'msgtype'=>'text','text'=>['content'=>"VWx0h"]];
//            switch ($lang){     //语言
//                case 1:
//                    $msg_default['customservice'] = ['kf_account'=>'kf2002@sprosoft'];
//                    $result[] = $msg_default;
//                    break;
//                case 2:
//                    $msg_default['customservice'] = ['kf_account'=>'kf2001@sprosoft'];
//                    $result[] = $msg_default;
//                    break;
//                default :
//                    $result[] = $msg_default;
//                    break;
//            }
//        }else 
        if(!isset($cache['is_end']) || $cache['is_end'] == 0){
            if(isset($cache['userinfo']) && $cache['userinfo'] == 1){    //需要手机用户邮箱
                $result = $this->suddenlyReply($openid);
            }else{
                $msg_default = ['touser'=>(string)$openid,'msgtype'=>'image','image'=>['media_id'=>"jCDZhRx-dnrMUjZGR8ApoHlidKAqZ1-prgXTIRpjuos"]];
                $msg_en = ['touser'=>(string)$openid,'msgtype'=>'text','text'=>['content'=>"RSLWF3"]];
                $msg_cn = ['touser'=>(string)$openid,'msgtype'=>'text','text'=>['content'=>'lanhN2']];
                switch ($lang){     //语言
                    case 1:
                        $msg_default['customservice'] = ['kf_account'=>'kf2002@sprosoft'];
                        $msg_en['customservice'] = ['kf_account'=>'kf2002@sprosoft'];
                        $result[] = $msg_default;
                        $result[] = $msg_en;
                        break;
                    case 2:
                        $msg_default['customservice'] = ['kf_account'=>'kf2001@sprosoft'];
                        $msg_cn['customservice'] = ['kf_account'=>'kf2001@sprosoft'];
                        $result[] = $msg_default;
                        $result[] = $msg_cn;
                        break;
                    default :
                        $result[] = $msg_default;
                        $result[] = $msg_en;
                        $result[] = $msg_cn;
                        break;
                }
            }
        }
        \App\Components\WxSend::timingMsgSend($openid,$result);
        Tools::wxMsgRecord($openid,['timing'=>0,'is_end'=>0]);//设置定时开
        return true;
    }
    
    /**
     * 邮箱输入错误消息回复
     */
    public function errorEmail($openid){
        $cache = Tools::wxMsgRecord($openid);
        $result = [];
        $lang = 0;
        if(isset($cache['lang'])){
            $lang = $cache['lang'];
        }else{
            $oThirdUser = new \App\Models\UserModels\ThirdUser();
            $thirdUserData = $oThirdUser->db_get(['openid'=>['=',$openid]]);
            if($thirdUserData){
                $oUser = new \App\Models\UserModels\User();
                $user_data = $oUser->db_get(['user_id'=>['=',$thirdUserData['user_id']]]);
                !empty($user_data) && $lang = $user_data['lang'];
            }
        }
        $msg_en = ['touser'=>(string)$openid,'msgtype'=>'text','text'=>['content'=>"o3pXT"]];
        $msg_cn = ['touser'=>(string)$openid,'msgtype'=>'text','text'=>['content'=>'vAqzV4']];
        switch ($lang){     //语言
            case 1:
                $msg_en['customservice'] = ['kf_account'=>'kf2002@sprosoft'];
                $result[] = $msg_en;
                break;
            case 2:
                $msg_cn['customservice'] = ['kf_account'=>'kf2001@sprosoft'];
                $result[] = $msg_cn;
                break;
            default :
                $result[] = $msg_en;
                $result[] = $msg_cn;
                break;
        }
        \App\Components\WxSend::timingMsgSend($openid,$result);
        return true;
    }
    
    /**
     * 提交完表单信息
     * 发送消息
     */
    public function submitForm($user_id){
        \XLog::info("in.", __FILE__, __FUNCTION__, __LINE__);
        $oThirdUser = new \App\Models\UserModels\ThirdUser();
        $thirdUserData = $oThirdUser->db_get(['user_id'=>['=',$user_id]]);
        if(!empty($thirdUserData)){
            $openid = $thirdUserData['openid'];
            $cache = Tools::wxMsgRecord($openid);
            $result = [];
            $lang = 0;
            if(isset($cache['lang'])){
                $lang = $cache['lang'];
            }else{
                $oUser = new \App\Models\UserModels\User();
                $user_data = $oUser->db_get(['user_id'=>['=',$user_id]]);
                !empty($user_data) && $lang = $user_data['lang'];
            }
            $msg_en = ['touser'=>(string)$openid,'msgtype'=>'text','text'=>['content'=>"gvAQo2"]];
            $msg_cn = ['touser'=>(string)$openid,'msgtype'=>'text','text'=>['content'=>'Nfs2Q1']];
            switch ($lang){     //语言
                case 1:
                    $msg_en['customservice'] = ['kf_account'=>'kf2002@sprosoft'];
                    $result[] = $msg_en;
                    break;
                case 2:
                    $msg_cn['customservice'] = ['kf_account'=>'kf2001@sprosoft'];
                    $result[] = $msg_cn;
                    break;
                default :
                    $result[] = $msg_en;
                    $result[] = $msg_cn;
                    break;
            }
            \App\Components\WxSend::timingMsgSend($openid,$result);
        }
        return true;
    }
}