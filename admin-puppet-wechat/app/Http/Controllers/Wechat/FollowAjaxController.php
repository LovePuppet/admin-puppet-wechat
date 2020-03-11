<?php
namespace App\Http\Controllers\Wechat;
use App\Http\Controllers\ApiBaseController;
use Illuminate\Http\Request;
use App\Models\WechatModels\Follow;
class FollowAjaxController extends ApiBaseController{
    
    public function __construct(Request $request) {
        parent::__construct($request);
    }
    
    public function saveData(){
        $msg_1 = [];
        $msg_1['msgtype'] = trim($this->params['type_1']);
        $msg_1['content'] = trim($this->params['content_1']);
        $msg_2 = [];
        $msg_2['msgtype'] = trim($this->params['type_2']);
        $msg_2['content'] = trim($this->params['content_2']);
        $msg_3 = [];
        $msg_3['msgtype'] = trim($this->params['type_3']);
        $msg_3['content'] = trim($this->params['content_3']);
        $content[] = $msg_1;
        $content[] = $msg_2;
        $content[] = $msg_3;
        $oFollow = new Follow();
        $followData = $oFollow->db_get(['type'=>['=',1],'status'=>['=',1]]);
        if(!empty($followData)){
            $data['content'] = json_encode($content);
            $data['update_time'] = time();
            if($oFollow->db_update('',$followData['follow_id'],$data)){
                $this->data = true;
            }else{
                $this->code = 1;
                $this->msg = '保存失败';
            }
        }else{
            $data['type'] = 1;
            $data['content'] = json_encode($content);
            $data['create_time'] = time();
            $data['update_time'] = time();
            if($oFollow->db_insert($data)){
                $this->data = true;
            }else{
                $this->code = 1;
                $this->msg = '保存失败';
            }
        }
        $this->boot();
    }
    
    public function repeatSaveData(){
        $msg_1 = [];
        $msg_1['msgtype'] = trim($this->params['type_1']);
        $msg_1['content'] = trim($this->params['content_1']);
        $msg_2 = [];
        $msg_2['msgtype'] = trim($this->params['type_2']);
        $msg_2['content'] = trim($this->params['content_2']);
        $msg_3 = [];
        $msg_3['msgtype'] = trim($this->params['type_3']);
        $msg_3['content'] = trim($this->params['content_3']);
        $content[] = $msg_1;
        $content[] = $msg_2;
        $content[] = $msg_3;
        $oFollow = new Follow();
        $followData = $oFollow->db_get(['type'=>['=',2],'status'=>['=',1]]);
        if(!empty($followData)){
            $data['content'] = json_encode($content);
            $data['update_time'] = time();
            if($oFollow->db_update('',$followData['follow_id'],$data)){
                $this->data = true;
            }else{
                $this->code = 1;
                $this->msg = '保存失败';
            }
        }else{
            $data['type'] = 2;
            $data['content'] = json_encode($content);
            $data['create_time'] = time();
            $data['update_time'] = time();
            if($oFollow->db_insert($data)){
                $this->data = true;
            }else{
                $this->code = 1;
                $this->msg = '保存失败';
            }
        }
        $this->boot();
    }
}