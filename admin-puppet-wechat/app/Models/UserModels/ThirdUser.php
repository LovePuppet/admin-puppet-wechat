<?php
namespace App\Models\UserModels;
use App\Models\ParentModel;
use DB;
/**
 * 微信用户model
 * @author puppet
 */
class ThirdUser extends ParentModel{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'third_user';

    /**
     * DB::直接操作的数据库表名
     * @var string
     */
    protected $db_table = 'pt_third_user';

    /**
     * 主键 默认id。
     *
     * @var string
     */
    protected $primaryKey = 'third_user_id';
    
    /**
     * 指定是否模型应该被戳记时间。
     *
     * @var bool
     */
    public $timestamps = false;
  
  /**
   * 获取用户编号数组中用户的openid
   */
  public function getOpenidFromUserIds($user_arr){
    $user_ids = implode(',', $user_arr);
    $sql = "SELECT openid FROM {$this->db_table} WHERE user_id in ($user_ids);";
    return DB::select($sql);
  }
}