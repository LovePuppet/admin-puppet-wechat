<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\ApiBaseController;
use Illuminate\Http\Request;
use App\Models\UserModels\User;
use App\Components\Tools;
use App\Components\WxTools;
class UserAjaxController extends ApiBaseController {

  public function __construct(Request $request) {
    parent::__construct($request);
  }

  /**
   * 列表
   * ajax数据
   */
  public function dataList(){
    $oUser = new User;
    $result = $oUser->getPageData($this->params);
    echo json_encode($result);
  }

  public function info() {
    if(isset($this->params['id']) && intval($this->params['id']) > 0){
      $oUser = new User();
      $userData = $oUser->db_get(['user_id'=>['=',$this->params['id']]]);
      if(!empty($userData)){
        $userData['nick_name'] = json_decode($userData['nick_name']);
        $userData['subscribe_scene'] = Tools::subscribeScene($userData['subscribe_scene']);
        $userData['create_time'] = date('Y-m-d H:i:s',$userData['create_time']);
        $userData['subscribe_time'] = date('Y-m-d H:i:s',$userData['subscribe_time']);
        $userData['channel_name'] = '';
        if($userData['channel_id']){
          $oChannel = new \App\Models\UserModels\Channel();
          $channel_data = $oChannel->db_get(['channel_id'=>['=',$userData['channel_id']],'status'=>['=',1]]);
          !empty($channel_data) && $userData['channel_name'] = $channel_data['channel_name'];
        }
        $this->data = $userData;
      }else{
        $this->code = 1;
        $this->msg = '数据不存在';
      }
    }else{
      $this->code = 1;
      $this->msg = '缺少数据';
    }
    $this->boot();
  }
    
  public function userSync($params = []){
    set_time_limit(0);
    ini_set('memory_limit','500M');
    \XLog::info("in.params=".json_encode($params), __FILE__, __FUNCTION__, __LINE__);
    $result = WxTools::getUserList($params);
    \XLog::info("in.userList=".$result, __FILE__, __FUNCTION__, __LINE__);
    $data = json_decode($result,true);
    if(isset($data['errcode'])){
      $this->code = 1;
      $this->msg = $data['errmsg'];
    }else{
      if(!empty($data['data']['openid'])){
        $oUser = new User();
        $oThirdUser = new \App\Models\UserModels\ThirdUser();
        foreach($data['data']['openid'] as $openid){
          $thirdUserData = $oThirdUser->db_get(['openid'=>['=',$openid]]);
//          if(!empty($thirdUserData)){
//            $oUser->updateUser($openid,$thirdUserData['user_id']);
//          }else{
//            $oUser->addUser($openid);
//          }
          if(empty($thirdUserData)){
            $oUser->addUser($openid);
          }
        }
        $this->userSync(['next_openid'=>$data['next_openid']]);
      }
      $this->data = true;
    }
    $this->boot();
  }
}
