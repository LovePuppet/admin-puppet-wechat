<?php
namespace App\Http\Controllers\Wechat;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\WxTools;
class WxConfigController extends Controller{
    private $params;
    public function __construct(Request $request) {
        $this->params = $request->all();
        \XLog::info("in.params=".json_encode($this->params), __FILE__, __FUNCTION__, __LINE__);
    }
    
    public function index(){
        $result = WxTools::valid($this->params);
        if(!$result){
            return;
        }
        /*微信公众号开发者中心服务器配置*/
        if (isset($this->params['echostr']) && !empty($this->params['echostr'])) {
            echo trim($this->params['echostr']);
            return;
    	}
        $strPost = $GLOBALS["HTTP_RAW_POST_DATA"];
        echo WxTools::Receive($strPost);
        \XLog::info("out.");
    }
    
    public function oauth(){
        if(isset($this->params['code']) && !empty($this->params['code']) && isset($this->params['state']) && intval($this->params['state']) == 2018){
            //第二步：通过code换取网页授权access_token
            $result = WxTools::oauthGetAccessToken($this->params['code']);
            $result = json_decode($result,true);
            if(isset($result['errcode'])){
                WxTools::oauthGetCode();
            }else{
                $openid = $result['openid'];
                $oUser = new \App\Models\UserModels\User();
                $user_id = $oUser->addUserInfo($openid);
                return view('wechat/user/form',['user_id'=>$user_id]);
            }
        }else{
            //第一步：用户同意授权，获取code
            WxTools::oauthGetCode();
        }
    }
    
    public function saveData(){
      \XLog::info("in.", __FILE__, __FUNCTION__, __LINE__);
      $result = ['code'=>0,'msg'=>''];
      if(isset($this->params['user_id']) && intval($this->params['user_id']) > 0 && isset($this->params['name']) && !empty($this->params['name'])
         && isset($this->params['email']) && !empty($this->params['email']) && isset($this->params['address']) && !empty($this->params['address'])
         && isset($this->params['mobile']) && !empty($this->params['mobile'])){
          $userData['real_name'] = $this->params['name'];
          $userData['mobile'] = $this->params['mobile'];
          $userData['email'] = $this->params['email'];
          $userData['address'] = $this->params['address'];
          $userData['remark'] = $this->params['remark'];
          $oUser = new \App\Models\UserModels\User();
          if(!$oUser->db_update('',$this->params['user_id'],$userData)){
              $result['code'] = 1;
              $result['msg'] = '保存失败';
          }else{  //推送消息
            //微信回复
            $oRobot = new \App\Models\RobotModels\Robot();
            \XLog::info("in.user_id=".$this->params['user_id'], __FILE__, __FUNCTION__, __LINE__);
            $oRobot->submitForm($this->params['user_id']);
            $oEmail = new \App\Models\UserModels\Email();
            $oEmail->sendEmail($this->params['user_id']);
          }
      }else{
        $result['code'] = 1;
        $result['msg'] = '数据有误';
      }
      \XLog::info("out.result=".json_encode($result), __FILE__, __FUNCTION__, __LINE__);
      echo json_encode($result);
    }
}