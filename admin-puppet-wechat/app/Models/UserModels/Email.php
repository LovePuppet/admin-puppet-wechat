<?php
namespace App\Models\UserModels;
use App\Models\ParentModel;
use Mail;
use DB;
/**
 * 邮箱model
 * @author puppet
 */
class Email extends ParentModel{
  /**
   * 与模型关联的数据表。
   *
   * @var string
   */
  protected $table = 'email';

  /**
   * DB::直接操作的数据库表名
   * @var string
   */
  protected $db_table = 'pt_email';

  /**
   * 主键 默认id。
   *
   * @var string
   */
  protected $primaryKey = 'email_id';

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
    $cou_sql = "SELECT count(*) as count FROM {$this->db_table} WHERE `status` >= ?";
    $sql = "SELECT email_id,`name`,email,create_time,`status` FROM {$this->db_table} WHERE `status` >= ?";
    $values = [0];
    if(isset($params['search']['value']) && !empty($params['search']['value'])){
      $cou_sql .= " AND (`name` like '%".$params['search']['value']."%' OR email like '%".$params['search']['value']."%')";
      $sql .= " AND (`name` like '%".$params['search']['value']."%' OR email like '%".$params['search']['value']."%')";
    }
    $count = DB::select($cou_sql,$values);
    $count = isset($count[0]['count']) ? $count[0]['count'] : 0;
    if(isset($params['order']) && !empty($params['order'])){
      $order_db = ['email_id'];
      $sql .= " ORDER BY ";
      foreach ($params['order'] as $order){
        $result = isset($order_db[$order['column']]) ? $order_db[$order['column']] : '';
        !empty($result) && $sql .= $result.' '.$order['dir'].',';
      }
      $sql = substr($sql,0,-1);
    }
    $sql .= " LIMIT $limit_sta,$limit_num";
    $res['data'] = DB::select($sql,$values);
    $res['total'] = $count;
    //总条数记录
    return $res;
  }
  
  public function sendEmail($user_id){
    \XLog::info("in.user_id=".$user_id, __FILE__, __FUNCTION__, __LINE__);
    $sql = "SELECT email FROM {$this->db_table} WHERE `status` = ?";
    $result = DB::select($sql,[1]);
    if(!empty($result)){
      $oUser = new User();
      $user_data = $oUser->db_get(['user_id'=>['=',$user_id]]);
      $data['nick_name'] = !empty($user_data) ? json_decode($user_data['nick_name']) : '';
      $data['head_img'] = !empty($user_data) ? $user_data['head_img'] : '';
      $data['real_name'] = !empty($user_data) ? $user_data['real_name'] : '';
      $data['mobile'] = !empty($user_data) ? $user_data['mobile'] : '';
      $data['email'] = !empty($user_data) ? $user_data['email'] : '';
      $data['address'] = !empty($user_data) ? $user_data['address'] : '';
      $data['remark'] = !empty($user_data) ? $user_data['remark'] : '';
      foreach ($result as $email){
        $flag = Mail::send('user/email/email',['data'=>$data],function($message) use ($email){
          $to = $email['email'];
          $message ->to($to)->subject('用户提交信息通知');
        });
        if($flag){
          \XLog::info("out.发送成功! email=".$email['email'], __FILE__, __FUNCTION__, __LINE__);
        }else{  
          \XLog::info("out.发送失败! email=".$email['email'], __FILE__, __FUNCTION__, __LINE__);
        }
      }
    }
    return true;
  }
}