<?php
namespace App\Models\StatisticsModels;
use App\Models\ParentModel;
use DB;
/**
 * 用户增减数据model
 * @author puppet
 */
class UserSummary extends ParentModel{
  /**
   * 与模型关联的数据表。
   *
   * @var string
   */
  protected $table = 'user_summary';

  /**
   * DB::直接操作的数据库表名
   * @var string
   */
  protected $db_table = 'pt_user_summary';

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