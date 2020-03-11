<?php
namespace App\Models\StatisticsModels;
use App\Models\ParentModel;
use DB;
/**
 * 累计用户数据model
 * @author puppet
 */
class UserCumulate extends ParentModel{
  /**
   * 与模型关联的数据表。
   *
   * @var string
   */
  protected $table = 'user_cumulate';

  /**
   * DB::直接操作的数据库表名
   * @var string
   */
  protected $db_table = 'pt_user_cumulate';

  /**
   * 主键 默认id。
   *
   * @var string
   */
  protected $primaryKey = 'id';

  /**
   * 指定是否模型应该被戳记时间。
   *
   * @var bool
   */
  public $timestamps = false;
}