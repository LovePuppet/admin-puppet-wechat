<?php
namespace App\Models\UserModels;
use App\Models\ParentModel;
use DB;
/**
 * 用户标签关联model
 * @author puppet
 */
class UserTagRelation extends ParentModel{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'user_tag_relation';

    /**
     * DB::直接操作的数据库表名
     * @var string
     */
    protected $db_table = 'pt_user_tag_relation';

    /**
     * 主键 默认id。
     *
     * @var string
     */
    protected $primaryKey = 'user_tag_relation_id';
    
    /**
     * 指定是否模型应该被戳记时间。
     *
     * @var bool
     */
    public $timestamps = false;
    
  /**
   * 获取某标签下所有用户
   */
  public function getPageData($params){
    $tag_name = '';
    $res = false;
    $limit_num = (isset($params['limit']) && intval($params['limit'])>0 && intval($params['limit']) <= 100) ? $params['limit'] : 10;
    $limit_sta = 0;
    $page = 1;
    if (isset($params['page']) && intval($params['page']) > 0) {
      $limit_sta = intval($limit_num * intval($params['page'] - 1));
      $page = intval($params['page']);
    }
    $cou_sql = "SELECT count(*) as count FROM pt_user a,{$this->db_table} b WHERE a.user_id = b.user_id AND a.`status` = ?";
    $sql = "SELECT b.user_tag_relation_id,a.user_id,a.head_img,a.nick_name,b.create_time FROM pt_user a,{$this->db_table} b WHERE a.user_id = b.user_id AND a.`status` = ?";
    $values = [1];
    if(isset($params['tag_id']) && !empty($params['tag_id'])){
      $cou_sql .= " AND b.tag_id = ?";
      $sql .= " AND b.tag_id = ?";
      $values[] = $params['tag_id'];
      $oTag = new Tag();
      $tag_data = $oTag->db_get(['tag_id'=>['=',$params['tag_id']],'status'=>['=',1]]);
      !empty($tag_data) && $tag_name = $tag_data['name'];
    }
    $count = DB::select($cou_sql,$values);
    $count = isset($count[0]['count']) ? $count[0]['count'] : 0;
    $sql .= " LIMIT $limit_sta,$limit_num";
    $order_list = DB::select($sql,$values);
    $res['data'] = $order_list;
    if(!empty($res['data'])){
      foreach ($res['data'] as $key => $val){
        $val['name'] = $tag_name;
        $val['nick_name'] = json_decode($val['nick_name']);
        $res['data'][$key] = $val;
      }
    }
    $res['total'] = $count;
    //总条数记录
    return $res;
  }
  
  public function getAllUserFromTag($tag_id){
    $sql = "SELECT a.user_id,a.head_img,a.nick_name,b.create_time FROM pt_user a,{$this->db_table} b WHERE a.user_id = b.user_id AND a.`status` = ? AND b.tag_id = ?";
    return DB::select($sql,[1,$tag_id]);
  }
  
  public function deleteAllUserFromTag($tag_id){
    $sql = "DELETE FROM {$this->db_table} WHERE tag_id = ?";
    return DB::delete($sql,[$tag_id]);
  }
}