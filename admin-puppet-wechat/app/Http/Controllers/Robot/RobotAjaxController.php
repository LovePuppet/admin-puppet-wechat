<?php
namespace App\Http\Controllers\Robot;
use App\Http\Controllers\ApiBaseController;
use Illuminate\Http\Request;
use App\Models\RobotModels\Robot;
class RobotAjaxController extends ApiBaseController{
    
    public function __construct(Request $request) {
        parent::__construct($request);
    }
    
    /**
     * 管理员列表
     * ajax数据
     */
    public function dataList(){
        $oRobot = new Robot;
        $result = $oRobot->getPageData($this->params);
        echo json_encode($result);
    }
    
    /**
     * 操作用户状态
     * 开启/关闭
     */
    public function deleteData(){
        if(isset($this->params['id']) && intval($this->params['id']) > 0 && isset($this->params['status'])){
            $oRobot = new Robot;
            $result = $oRobot->db_get(['robot_id'=>['=',$this->params['id']]]);
            if(!empty($result)){
                if($oRobot->db_update('',$this->params['id'],['status'=>$this->params['status']])){
                    $this->data = true;
                }else{
                    $this->code = 1;
                    $this->msg = '操作失败';
                }
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
    
    public function createSave(){
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
        $oRobot = new Robot();
        $keyword = trim($this->params['keyword']);
        $keyword_arr = explode(' ', $keyword);
        $data['keyword'] = !empty($keyword) ? json_encode($keyword_arr,JSON_UNESCAPED_UNICODE) : '';
        $data['content'] = json_encode($content);
        $data['is_lang'] = intval($this->params['is_lang']);
        $data['is_customer_type'] = intval($this->params['is_customer_type']);
        $data['userinfo'] = intval($this->params['userinfo']);
        $data['is_end'] = intval($this->params['is_end']);
        $data['create_time'] = time();
        $data['fid'] = isset($this->params['fid']) ? intval($this->params['fid']) : 0;
        if($oRobot->db_insert($data)){
            $this->data = true;
        }else{
            $this->code = 1;
            $this->msg = '保存失败';
        }
        $this->boot();
    }
    
    public function editSave($id){
        if(intval($id) > 0){
            $oRobot = new Robot();
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
            $keyword = trim($this->params['keyword']);
            $keyword_arr = explode(' ', $keyword);
            $data['keyword'] = !empty($keyword) ? json_encode($keyword_arr,JSON_UNESCAPED_UNICODE) : '';
            $data['content'] = json_encode($content);
            $data['is_lang'] = intval($this->params['is_lang']);
            $data['is_customer_type'] = intval($this->params['is_customer_type']);
            $data['userinfo'] = intval($this->params['userinfo']);
            $data['is_end'] = intval($this->params['is_end']);
            $data['fid'] = isset($this->params['fid']) ? intval($this->params['fid']) : 0;
            if($oRobot->db_update('',$id,$data)){
                $this->data = true;
            }else{
                $this->code = 1;
                $this->msg = '保存失败';
            }
        }else{
            $this->code = 1;
            $this->msg = '缺少数据';
        }
        $this->boot();
    }
}