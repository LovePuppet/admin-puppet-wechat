<?php
namespace App\Http\Controllers\Material;
use App\Http\Controllers\ApiBaseController;
use Illuminate\Http\Request;
use App\Models\MaterialModels\Image;
use App\Components\WxTools;
use App\Components\ImageTools;
class ImageAjaxController extends ApiBaseController{
    
    public function __construct(Request $request) {
        parent::__construct($request);
    }
    
    /**
     * 列表
     * ajax数据
     */
    public function dataList(){
        $oImage = new Image;
        $result = $oImage->getPageData($this->params);
        echo json_encode($result);
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
                $oImage = new Image;
                $result = $oImage->db_get(['image_id'=>['=',$this->params['id']]]);
                if(!empty($result)){
                    if($oImage->db_update('',$this->params['id'],['status'=>$this->params['status']])){
                        WxTools::deleteWxMaterial($result['media_id']);
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
    
    public function imageSync(){
        $params = ['type'=>'image','offset'=>0,'count'=>20];
        $result = WxTools::wxMaterial($params);
        $data = json_decode($result,true);
        if(isset($data['errcode'])){
            $this->code = 1;
            $this->msg = $data['errmsg'];
        }else{
            $this->saveImage($data['item']);
            $page = ceil($data['total_count']/20);
            if($page > 1){
                for($i = 2;$i<=$page;$i++){
                    $params = ['type'=>'image','offset'=>20*($i-1),'count'=>20];
                    $result = WxTools::wxMaterial($params);
                    $data = json_decode($result,true);
                    $this->saveImage($data['item']);
                }
            }
            $this->data = true;
        }
        $this->boot();
    }
    
    public function saveImage($data){
        $result = false;
        if(!empty($data) && is_array($data)){
            $oImage = new Image();
            $oImageTools = new ImageTools();
            foreach ($data as $val){
                $imgData = $oImage->db_get(['media_id'=>['=',$val['media_id']],'status'=>['=',1]]);
                if(empty($imgData)){
                    $image = [];
                    $image['name'] = $val['name'];
                    $image['media_id'] = $val['media_id'];
                    $image['url'] = $oImageTools->getImage($val['url']);
                    $image['create_time'] = $val['update_time'];
                    $image['status'] = 1;
                    $oImage->db_insert($image);
                }
            }
        }
        return $result;
    }
    
    public function getData(){
        if(isset($this->params['media_id']) && !empty($this->params['media_id'])){
            $oImage = new Image();
            $data = $oImage->db_get(['media_id'=>['=',$this->params['media_id']],'status'=>['=',1]]);
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
}