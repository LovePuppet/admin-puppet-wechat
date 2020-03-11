<?php
namespace App\Http\Controllers\Material;
use App\Http\Controllers\ApiBaseController;
use Illuminate\Http\Request;
use App\Models\MaterialModels\Text;
use App\Components\Tools;
class TextAjaxController extends ApiBaseController{
    
    public function __construct(Request $request) {
        parent::__construct($request);
    }
    
    /**
     * 列表
     * ajax数据
     */
    public function dataList(){
        $oText = new Text;
        $result = $oText->getPageData($this->params);
        echo json_encode($result);
    }
    
    /**
     * 保存菜单方法
     */
    public function saveData(Request $request){
        if(!$request->session()->has('user')){
            $this->code = 1;
            $this->msg = '登录已过时，请重新登录';
        }else{
            if(isset($this->params['content']) && !empty($this->params['content'])){
                $oText = new Text;
                if(isset($this->params['id']) && intval($this->params['id']) > 0){
                    $data['content'] = trim($this->params['content']);
                    if($oText->db_update('',intval($this->params['id']),$data)){
                        $this->msg = '保存成功';
                    }else{
                        $this->code = 1;
                        $this->msg = '保存失败';
                    }
                }else{
                    $count = $oText->getCount() + 1;
                    $token = Tools::token($count);
                    $data['token'] = $token;
                    $data['crc_token'] = Tools::crcToken($token);
                    $data['content'] = trim($this->params['content']);
                    $data['create_time'] = time();
                    $data['status'] = 1;
                    if($oText->db_insert($data)){
                        $this->msg = '保存成功';
                    }else{
                        $this->code = 1;
                        $this->msg = '保存失败';
                    }
                }
            }else{
                $this->code = 1;
                $this->msg = '缺少数据';
            }
        }
        $this->boot();
    }
    
    public function getData(){
        if(isset($this->params['id']) && intval($this->params['id']) > 0){
            $oText = new Text;
            $data = $oText->db_get(['text_id'=>['=',$this->params['id']],'status'=>['=',1]]);
            if(!empty($data)){
                $this->data = $data;
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
    
    public function getDataByCrctoken(){
        if(isset($this->params['crc_token']) && !empty($this->params['crc_token'])){
            $oText = new Text;
            $data = $oText->db_get(['crc_token'=>['=',$this->params['crc_token']],'status'=>['=',1]]);
            if(!empty($data)){
                $this->data = $data;
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
    
    /**
     * 操作状态
     * 开启/关闭/删除
     */
    public function deleteData(Request $request){
        if(!$request->session()->has('user')){
            $this->code = 1;
            $this->msg = '登录已过时，请重新登录';
        }else{
            if(isset($this->params['id']) && intval($this->params['id']) > 0 && isset($this->params['status'])){
                $oText = new Text;
                $result = $oText->db_get(['text_id'=>['=',$this->params['id']]]);
                if(!empty($result)){
                    if($oText->db_update('',$this->params['id'],['status'=>$this->params['status']])){
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
        }
        $this->boot();
    }
}

