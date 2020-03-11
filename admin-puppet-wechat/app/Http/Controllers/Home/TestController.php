<?php
namespace App\Http\Controllers\Home;
use App\Http\Controllers\Controller;
use Excel;
use DB;
class TestController extends Controller{
  public function __construct() {
    //Log::info('[file:'.__FILE__.']'.'[func:'.__FUNCTION__.']'.'[line:'.__LINE__.']:in.',['get='=>$_GET,'post='=>$_POST]);
  }
  /**
   * 测试
   */
  public function index(){
    $cellData[] = ['序号','昵称','头像','性别','国家','省份','城市','来源','关注时间'];
    $sql = "SELECT user_id,nick_name,head_img,sex,country,province,city,subscribe_scene,subscribe_time FROM pt_user";
    $data = DB::select($sql);
    if(!empty($data)){
      foreach ($data as $val){
        $nick_name = json_decode($val['nick_name'],true);
        $sex = '';
        switch ($val['sex']){
          case 1:
            $sex = '男';
            break;
          case 2:
            $sex = '女';
            break;
          case 0:
            $sex = '未知';
            break;
          default :
            $sex = '未知';
            break;
        }
        $subscribe_scene = '';
        switch ($val['subscribe_scene']){
          case 'ADD_SCENE_SEARCH':
            $subscribe_scene = '公众号搜索';
            break;
          case 'ADD_SCENE_ACCOUNT_MIGRATION':
            $subscribe_scene = '公众号迁移';
            break;
          case 'ADD_SCENE_PROFILE_CARD':
            $subscribe_scene = '名片分享';
            break;
          case 'ADD_SCENE_QR_CODE':
            $subscribe_scene = '扫描二维码';
            break;
          case 'ADD_SCENE_PROFILE_LINK':
            $subscribe_scene = '图文页内名称点击';
            break;
          case 'ADD_SCENE_PROFILE_ITEM':
            $subscribe_scene = '图文页右上角菜单';
            break;
          case 'ADD_SCENE_PAID':
            $subscribe_scene = '支付后关注';
            break;
          case 'ADD_SCENE_OTHERS':
            $subscribe_scene = '其他';
            break;
        }
        $subscribe_time = date('Y-m-d H:i:s',$val['subscribe_time']);
        $cellData[] = [$val['user_id'],$nick_name,$val['head_img'],$sex,$val['country'],$val['province'],$val['city'],$subscribe_scene,$subscribe_time];
      }
    }
    Excel::create('微信用户信息',function($excel) use ($cellData){
      $excel->sheet('info', function($sheet) use ($cellData){
        $sheet->rows($cellData);
      });
    })->export('xlsx');
  }
}
