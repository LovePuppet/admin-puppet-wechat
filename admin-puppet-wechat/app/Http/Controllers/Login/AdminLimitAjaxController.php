<?php

namespace App\Http\Controllers\Login;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminModels\AdminLimit;
class AdminLimitAjaxController extends Controller{
    private $code = false;
    private $parames;
    private $message = '';
    private $data = '';
    
    public function __construct(Request $request){
        $this->parames = $request->all();
    }
    
    /**
     * 管理员列表
     * ajax数据
     */
    public function dataList(){
        $oAdminLimit = new AdminLimit;
        $result = $oAdminLimit->getPageData($this->parames);
        echo json_encode($result);
    }
    
    /**
     * 验证权限url是否存在
     */
    public function validAdminLimitUrl(){
        if(isset($this->parames['limit_url']) && !empty($this->parames['limit_url'])){
            $oAdminLimit = new AdminLimit;
            $result = $oAdminLimit->db_get(['limit_url'=>['=',$this->parames['limit_url']],'status'=>['=',1]]);
            if(!empty($result)){
                $this->code = true;
                $this->message = '权限地址已经存在';
            }else{
                $this->data = true;
            }
        }else{
            $this->code = true;
            $this->message = '缺少数据';
        }
        echo json_encode(['code'=>$this->code,'message'=>$this->message,'data'=>$this->data]);
    }
    /**
     * 操作用户状态
     * 开启/关闭
     */
    public function deleteAdminLimit(Request $request){
        if(!$request->session()->has('user')){
            $this->code = true;
            $this->message = '登录已过时，请重新登录';
        }else{
            $url = $_SERVER['REQUEST_URI'];
            $result = \App\Components\Tools::urlLimit($url,$request);
            if(!$result){
                $this->code = true;
                $this->message = '无权限操作';
            }else{
                if(isset($this->parames['id']) && intval($this->parames['id']) > 0 && isset($this->parames['status'])){
                    $oAdminLimit = new AdminLimit;
                    $result = $oAdminLimit->db_get(['admin_limit_id'=>['=',$this->parames['id']]]);
                    if(!empty($result)){
                        if($oAdminLimit->db_update('',$this->parames['id'],['status'=>$this->parames['status']])){
                            $this->data = true;
                        }else{
                            $this->code = true;
                            $this->message = '操作失败';
                        }
                    }else{
                        $this->code = true;
                        $this->message = '数据不存在';
                    }
                }else{
                    $this->code = true;
                    $this->message = '缺少数据';
                }
            }
        }
        echo json_encode(['code'=>$this->code,'message'=>$this->message,'data'=>$this->data]);
    }
}