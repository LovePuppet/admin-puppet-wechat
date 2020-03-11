<?php
namespace App\Models\UserModels;
use App\Models\ParentModel;
use DB;
use App\Components\WxTools;
use App\Components\Tools;
/**
 * 用户model
 * @author puppet
 */
class User extends ParentModel{
  /**
   * 与模型关联的数据表。
   *
   * @var string
   */
  protected $table = 'user';

  /**
   * DB::直接操作的数据库表名
   * @var string
   */
  protected $db_table = 'pt_user';

  /**
   * 主键 默认id。
   *
   * @var string
   */
  protected $primaryKey = 'user_id';

  /**
   * 指定是否模型应该被戳记时间。
   *
   * @var bool
   */
  public $timestamps = false;

  public function getCount(){
    return DB::table($this->table)->count();
  }
    
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
    $sql = "SELECT user_id,head_img,lang,customer_type,nick_name,real_name,mobile,email,company,subscribe_time FROM {$this->db_table} WHERE `status` = ?";
    $values = [1];
//        if(isset($params['search']['value']) && !empty($params['search']['value'])){
//          $nick_name_ = json_encode($params['search']['value']);
//          $nick_name_ = substr($nick_name_, 1 ,-1);//去掉json字符串首尾的双引号
//          $nick_name_ = str_replace('\\', '_', $nick_name_);//将json字符串\替换成_
//          $nick_name_ = explode(' ', $nick_name_);//是否有空格
//          if(is_array($nick_name_)){
//            $nick_name_ = !empty($nick_name_[0]) ? $nick_name_[0] : $nick_name_[1];
//          }
//          $cou_sql .= " AND nick_name like '%{$nick_name_}%'";
//          $sql .= " AND nick_name like '%{$nick_name_}%'";
//        }
    if(isset($params['channel_id']) && intval($params['channel_id']) > 0){
      $cou_sql .= " AND channel_id = {$params['channel_id']}";
      $sql .= " AND channel_id = {$params['channel_id']}";
    }
    if(isset($params['nick_name']) && !empty($params['nick_name'])){
      $nick_name = json_encode($params['nick_name']);
      $nick_name = substr($nick_name, 1 ,-1);//去掉json字符串首尾的双引号
      $nick_name = str_replace('\\', '_', $nick_name);//将json字符串\替换成_
      $nick_name = explode(' ', $nick_name);//是否有空格
      if(is_array($nick_name)){
          $nick_name = !empty($nick_name[0]) ? $nick_name[0] : $nick_name[1];
      }
      $cou_sql .= " AND nick_name like '%{$nick_name}%'";
      $sql .= " AND nick_name like '%{$nick_name}%'";
    }
    if(isset($params['mobile']) && !empty($params['mobile'])){
      $cou_sql .= " AND mobile like '%{$params['mobile']}%'";
      $sql .= " AND mobile like '%{$params['mobile']}%'";
    }
    if(isset($params['email']) && !empty($params['email'])){
      $cou_sql .= " AND email like '%{$params['email']}%'";
      $sql .= " AND email like '%{$params['email']}%'";
    }
    if(isset($params['real_name']) && !empty($params['real_name'])){
      $cou_sql .= " AND real_name like '%{$params['real_name']}%'";
      $sql .= " AND real_name like '%{$params['real_name']}%'";
    }
    if(isset($params['lang']) && intval($params['lang']) > 0){
      $cou_sql .= " AND lang = ?";
      $sql .= " AND lang = ?";
      $values[] = intval($params['lang']);
    }
    if(isset($params['customer_type']) && intval($params['customer_type']) > 0){
      $cou_sql .= " AND customer_type = ?";
      $sql .= " AND customer_type = ?";
      $values[] = intval($params['customer_type']);
    }
    $count = DB::select($cou_sql,$values);
    $count = isset($count[0]['count']) ? $count[0]['count'] : 0;
    if(isset($params['order']) && !empty($params['order'])){
      $order_db = ['user_id'];
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
    if(!empty($res['data'])){
      foreach ($res['data'] as $key => $val){
        $val['nick_name'] = json_decode($val['nick_name']);
        $res['data'][$key] = $val;
      }
    }
    $res['total'] = $count;
    //总条数记录
    return $res;
  }
    
  /**
   * 根据用户的openid
   * 获取用户的微信昵称
   */
  public function getNickname($openid){
    $nickname = '';
    $oThirdUser = new ThirdUser();
    $thirdUserData = $oThirdUser->db_get(['openid'=>['=',$openid]]);
    if(!empty($thirdUserData)){
      $userData = $this->db_get(['user_id'=>['=',$thirdUserData['user_id']]]);
      $nickname = !empty($userData) ? json_decode($userData['nick_name']) : '';
    }else{
      $nickname = $this->addUser($openid);
    }
    return $nickname;
  }
    
  public function addUser($openid,$channel_id = 0){
    $nickname = '';
    $result = WxTools::getUserInfo($openid);
    $userinfo = json_decode($result,true);
    if(!isset($userinfo['errcode']) && isset($userinfo['nickname'])){
      $nickname = $userinfo['nickname'];
      $count = $this->getCount() + 1;
      $userData['token'] = Tools::token($count);
      $userData['nick_name'] = json_encode($userinfo['nickname']);
      if($channel_id){
        $userData['channel_id'] = $channel_id;
      }
      $userData['sex'] = $userinfo['sex'];
      $userData['country'] = $userinfo['country'];
      $userData['province'] = $userinfo['province'];
      $userData['head_img'] = $userinfo['headimgurl'];
      $userData['subscribe_time'] = $userinfo['subscribe_time'];
      $userData['remark'] = $userinfo['remark'];
      $userData['group_id'] = $userinfo['groupid'];
      $userData['tagid_list'] = json_encode($userinfo['tagid_list']);
      $userData['subscribe_scene'] = $userinfo['subscribe_scene'];
      $userData['create_time'] = time();
      $user_id = $this->db_insertGetId($userData);
      $thirdUserData['user_id'] = $user_id;
      $thirdUserData['openid'] = $openid;
      $thirdUserData['unionid'] = isset($userinfo['unionid']) ? $userinfo['unionid'] : '';
      $thirdUserData['create_time'] = time();
      $oThirdUser = new ThirdUser();
      $oThirdUser->db_insert($thirdUserData);
    }
    return $nickname;
  }
    
  public function addUserInfo($openid){
    $user_id = 0;
    $oThirdUser = new ThirdUser();
    $thirdUserData = $oThirdUser->db_get(['openid'=>['=',$openid]]);
    if(!empty($thirdUserData)){
      $user_id = $thirdUserData['user_id'];
    }else{
      $result = WxTools::getUserInfo($openid);
      $userinfo = json_decode($result,true);
      if(!isset($userinfo['errcode']) && isset($userinfo['nickname'])){
        $count = $this->getCount() + 1;
        $userData['token'] = Tools::token($count);
        $userData['nick_name'] = json_encode($userinfo['nickname']);
        $userData['sex'] = $userinfo['sex'];
        $userData['country'] = $userinfo['country'];
        $userData['province'] = $userinfo['province'];
        $userData['head_img'] = $userinfo['headimgurl'];
        $userData['subscribe_time'] = $userinfo['subscribe_time'];
        $userData['remark'] = $userinfo['remark'];
        $userData['group_id'] = $userinfo['groupid'];
//                $userData['tagid_list'] = json_encode($userinfo['tagid_list']);
        $userData['subscribe_scene'] = $userinfo['subscribe_scene'];
        $userData['create_time'] = time();
        $user_id = $this->db_insertGetId($userData);
        $thirdUserData['user_id'] = $user_id;
        $thirdUserData['openid'] = $openid;
        $thirdUserData['unionid'] = isset($userinfo['unionid']) ? $userinfo['unionid'] : '';
        $thirdUserData['create_time'] = time();
        $oThirdUser = new ThirdUser();
        $oThirdUser->db_insert($thirdUserData);
      }else{
        echo '请先关注公众号';
        exit;
      }
    }
    return $user_id;
  }
  
  public function updateUser($openid,$user_id){
    $res = true;
    $result = WxTools::getUserInfo($openid);
    \XLog::info("in.userinfo=".$result, __FILE__, __FUNCTION__, __LINE__);
    $userinfo = json_decode($result,true);
    if(!isset($userinfo['errcode']) && isset($userinfo['nickname'])){
      $userData = $this->db_get(['user_id'=>['=',$user_id]]);
      if(!empty($userData)){
        $nickname = json_encode($userinfo['nickname']);
        if($nickname != $userData['nick_name'] || $userinfo['headimgurl'] != $userData['head_img']){
          $data['nick_name'] = $nickname;
          $data['head_img'] = $userinfo['headimgurl'];
          $this->db_update('',$user_id,$data);
        }
      }
    }
    return $res;
  }
    
  /**
   * 群发所有用户
   */
  public function getAllUser(){
    $sql = "SELECT a.user_id,b.openid FROM {$this->db_table} a,pt_third_user b WHERE a.user_id = b.user_id";
    return DB::select($sql);
  }
}