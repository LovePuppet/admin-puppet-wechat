<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use DB;
/**
 * 获取用户增减数据
 */
class GetusersummaryService extends Command{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'GetusersummaryService';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = '获取用户增减数据';

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle(){
    $this->getusersummary();
  }

  /**
   * 发送客服消息每分钟
   */
  public function getusersummary() {
    $yesterday = date("Y-m-d",strtotime("-1 day")); //昨天日期
    $params = [
      'begin_date' => $yesterday,
      'end_date' => $yesterday
    ];
    $result = \App\Components\WxTools::getusersummary($params);
    $data = json_decode($result,TRUE);
    if(!empty($data['list'])){
      $oUserSummary = new \App\Models\StatisticsModels\UserSummary();
      foreach ($data['list'] as $v){
        $oUSData = $oUserSummary->db_get(['ref_date'=>['=',$v['ref_date']],'user_source'=>['=',$v['user_source']]]);
        if(!empty($oUSData)){
          $oUserSummary->db_update('',$oUSData['id'],['new_user'=>$v['new_user'],'cancel_user'=>$v['cancel_user']]);
        }else{
          $val = [];
          $val['ref_date'] = $v['ref_date'];
          $val['user_source'] = $v['user_source'];
          $val['new_user'] = $v['new_user'];
          $val['cancel_user'] = $v['cancel_user'];
          $val['create_time'] = date('Y-m-d H:i:s');
          $oUserSummary->db_insert($val);
        }
      }
    }
    return true;
  }
}
