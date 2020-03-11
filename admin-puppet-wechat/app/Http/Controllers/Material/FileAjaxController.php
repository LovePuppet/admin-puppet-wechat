<?php
namespace App\Http\Controllers\Material;
use App\Http\Controllers\ApiBaseController;
use Illuminate\Http\Request;
use App\Models\MaterialModels\File;
class FileAjaxController extends ApiBaseController{
    public function __construct(Request $request) {
        parent::__construct($request);
    }
    
    /**
     * 列表
     * ajax数据
     */
    public function dataList(){
        $oFile = new File();
        $result = $oFile->getPageData($this->params);
        echo json_encode($result);
    }
    
    public function fileUpload(){
        $oFileTools = new \App\Components\FileTools;
        $result = $oFileTools->uploadMultiple('/file',false,false,true,true);
        if($result['error'] === false){
            $oFile = new File();
            foreach ($result['data'] as $val){
                $data['file_name'] = $val['name'];
                $data['file_url'] = '/file'.$val['path'];
                $data['file_size'] = $val['size'];
                $data['create_time'] = time();
                $oFile->db_insert($data);
            }
            $this->data = true;
        }else{
            $this->code = 1;
            $this->msg = '上传失败';
        }
        $this->boot();
    }
    
    public function deleteData(){
        if(isset($this->params['id']) && intval($this->params['id']) > 0){
            $oFile = new File();
            if($oFile->db_update('',$this->params['id'],['status'=>-1])){
                $this->data = true;
            }else{
                $this->code = 1;
                $this->msg = '删除失败';
            }
        }else{
            $this->code = 1;
            $this->msg = '缺少数据';
        }
        $this->boot();
    }
}