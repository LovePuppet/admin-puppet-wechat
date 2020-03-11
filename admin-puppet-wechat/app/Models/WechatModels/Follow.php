<?php
namespace App\Models\WechatModels;
use App\Models\ParentModel;
use DB;
/**
 * 微信关注model
 * @author puppet
 */
class Follow extends ParentModel{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'wechat_follow';

    /**
     * DB::直接操作的数据库表名
     * @var string
     */
    protected $db_table = 'pt_wechat_follow';

    /**
     * 主键 默认id。
     *
     * @var string
     */
    protected $primaryKey = 'follow_id';
    
    /**
     * 指定是否模型应该被戳记时间。
     *
     * @var bool
     */
    public $timestamps = false;
    
    public function getFollowData($openid,$eventKey=''){
        \XLog::info("in.openid=".$openid, __FILE__, __FUNCTION__, __LINE__);
        $result = [];
        $oThirdUser = new \App\Models\UserModels\ThirdUser();
        $thirdUserData = $oThirdUser->db_get(['openid'=>['=',$openid]]);
        $oUser = new \App\Models\UserModels\User();
        $channel_id = 0;
        if(!empty($eventKey)){
          $oChannel = new \App\Models\UserModels\Channel();
          $channel_data = $oChannel->db_get(['scene_str'=>['=',$eventKey],'status'=>['=',1]]);
          !empty($channel_data) && $channel_id = $channel_data['channel_id'];
        }
        if(!empty($thirdUserData)){ //老用户
          $result = $this->db_get(['type'=>['=',2],'status'=>['=',1]]);
          if($channel_id){
            $oUser->db_update('',$thirdUserData['user_id'],['channel_id'=>$channel_id]);
          }
        }else{  //新用户
          $oUser->addUser($openid,$channel_id);
          $result = $this->db_get(['type'=>['=',1],'status'=>['=',1]]);
        }
        \XLog::info("out.result=".json_encode($result), __FILE__, __FUNCTION__, __LINE__);
        return $result;
    }
}