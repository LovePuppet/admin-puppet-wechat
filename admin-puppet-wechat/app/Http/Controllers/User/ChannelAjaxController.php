<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\ApiBaseController;
use Illuminate\Http\Request;
use App\Models\UserModels\Channel;
use App\Components\Tools;
class ChannelAjaxController extends ApiBaseController {
    public function __construct(Request $request) {
        parent::__construct($request);
    }
    
    /**
     * 列表
     * ajax数据
     */
    public function dataList(){
        $oChannel = new Channel();
        $result = $oChannel->getPageData($this->params);
        echo json_encode($result);
    }
    
    /**
     * 验证渠道名是否存在
     */
    public function validChannelName(){
        $channel_name = trim($this->params['channel_name']);
        $id = intval($this->params['id']);
        $oChannel = new Channel();
        $data = [];
        if($id > 0){
            $data = $oChannel->db_get(['channel_name'=>['=',$channel_name],'status'=>['=',1],'channel_id'=>['!=',$id]]);
        }else{
            $data = $oChannel->db_get(['channel_name'=>['=',$channel_name],'status'=>['=',1]]);
        }
        $this->data = !empty($data) ? true : false;
        $this->boot();
    }
    
    public function deleteData(){
        $id = intval($this->params['id']);
        if($id > 0){
            $oChannel = new Channel();
            if($oChannel->db_update('',$id,['status'=>-1])){
                $this->data = true;
            }else{
                $this->code = 1;
                $this->msg = '保存失败';
            }
        }else{
            $this->code = 1;
            $this->msg = '数据不存在';
        }
        $this->boot();
    }
}