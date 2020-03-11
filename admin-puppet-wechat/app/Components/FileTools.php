<?php
namespace App\Components;
/**
 * 文件上传工具类
 * @author 郭钊林
 */
class FileTools{
    public $document_root = '';//根路径
    public $file_max_size = 102400000;   //200M
    public function __construct() {
        $this->document_root = $_SERVER['DOCUMENT_ROOT'].'/../../'.'puppet-file';
    }
    
    /**
     * 封装所有的单个文件上传方法
     * $path 文件路径
     * $ext 后缀名
     * $change_file_name 是否改变源文件名称
     * $dir_date 是否需要根据日期创建路径文件夹
     */
    public function uploadSim($path,$ext,$change_file_name = false,$dir_date = false){
        $result = ['error' => false,'message' => '','data' => []];
        $path = $dir_date ? ($this->document_root.$path.'/'.date('Ym').'/'.date('d').'/') : ($this->document_root.$path);
        $this->createFolder($path);
        if(isset($_FILES)){
            if($_FILES['file']['error'] != 0){
                $result['error'] = true;
                $result['message'] = '上传有误';
            }
            if($_FILES['file']['size'] > $this->file_max_size){
                $result['error'] = true;
                $result['message'] = '上传文件太大';
            }
            if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])){
                $name_arr = explode('.',$_FILES['file']['name']);
                $suffix = end($name_arr);
                if(strtolower($suffix) != $ext){
                    $result['error'] = true;
                    $result['message'] = '文件格式错误';
                }else{
                    $file_name = $change_file_name ? (md5($_FILES['file']['tmp_name']).'.'.$suffix) : $_FILES['file']['name'];
                    $file_path = $path.$file_name;
                    if(!move_uploaded_file($_FILES['file']['tmp_name'],$file_path)){
                        $result['error'] = true;
                        $result['message'] = '上传失败';
                    }else{
                        $result['data']['name'] = $_FILES['file']['name'];//原文件名
                        $result['data']['size'] = $_FILES['file']['size'];
                        $result['data']['path'] = $file_path;
                        $result['data']['apk_url'] = $dir_date ? ('/'.date('Ym').'/'.date('d').'/'.$file_name) : ('/'.$file_name);
                    }
                }
            }
        }
        return $result;
    }
    
    /**
     * 封装所有的多个文件上传方法
     * $path 文件路径
     * $ext 后缀名
     * $appoint_file_name 指定文件名称  不带后缀
     * $change_file_name 是否改变源文件名称 
     * $dir_date 是否需要根据日期创建路径文件夹
     */
    public function uploadMultiple($path,$ext = false,$appoint_file_name = false,$change_file_name = false,$dir_date = false){
        \XLog::info("in.path=".$path.", ext=".$ext.", appoint_file_name=".$appoint_file_name.", change_file_name=".$change_file_name.", dir_date=".$dir_date, __FILE__, __FUNCTION__, __LINE__);
        $result = ['error' => false,'message' => '','data' => []];
        $path = $dir_date ? ($this->document_root.$path.'/'.date('Ym').'/'.date('d').'/') : ($this->document_root.$path);
        $this->createFolder($path);
        if(isset($_FILES)){
            $file_error = false;
            foreach ($_FILES['file']['error'] as $error){
                $error != 0 && $file_error = true;
            }
            if($file_error){
                $result['error'] = true;
                $result['message'] = '上传失败';
            }else{
                $file_size = false;
                foreach ($_FILES['file']['size'] as $size){
                    $size > $this->file_max_size && $file_size = true;
                }
                if($file_size){
                    $result['error'] = true;
                    $result['message'] = '文件大小不能大于20M';
                }else{
                    $file_type = false;
                    if($ext){//需要判断文件后缀
                        foreach ($_FILES['file']['name'] as $file_name){
                            $name_arr = explode('.',$file_name);
                            $suffix = end($name_arr);
                            if(is_array($ext)){
                                !in_array(strtolower($suffix),$ext) && $file_type = true;
                            }else{
                                (strtolower($suffix) != $ext) && $file_type = true;
                            }
                        }
                    }
                    if($file_type){
                        $result['error'] = true;
                        $result['message'] = '格式错误';
                    }else{
                        $data = [];
                        $file_tmp_name = false;
                        foreach ($_FILES['file']['tmp_name'] as $key => $tmp_name){
                            $s_file_name = explode('.',$_FILES['file']['name'][$key]);
                            $s_ext = end($s_file_name);
                            if($appoint_file_name){
                                $file_name = $appoint_file_name.'.'.$s_ext;
                            }else{
                                $file_name = $change_file_name ? (md5($tmp_name).'.'.$s_ext) : $_FILES['file']['name'][$key];
                            }
                            $file_path = $path . '/' . $file_name;
                            $file_paths['name'] = $s_file_name[0];
                            $file_paths['size'] = $_FILES['file']['size'][$key];
                            $file_paths['path'] = $dir_date ? ('/'.date('Ym').'/'.date('d').'/'.$file_name) : $file_name;
                            if(!move_uploaded_file($tmp_name, $file_path)){
                                $file_tmp_name = true;
                            }
                            $data[] = $file_paths;
                        }
                        if($file_tmp_name){
                            $result['error'] = true;
                            $result['message'] = '上传失败';
                            $result['data'] = [];
                        }else{
                            $result['data'] = $data;
                        }
                    }
                }
            }
        }else{
            $result['error'] = true;
            $result['message'] = '请选择';
        }
        \XLog::info("out.result=".json_encode($result), __FILE__, __FUNCTION__, __LINE__);
        return $result;
    }
    
    public function uploadApk(){
        $result = ['error' => false,'message' => '','data' => []];
        $path = $this->appstore_apk.'/'.date('Ym').'/'.date('d').'/';
        $this->createFolder($path);
        if(isset($_FILES)){
            if($_FILES['file']['error'] != 0){
                $result['error'] = true;
                $result['message'] = '上传有误';
            }
            if($_FILES['file']['size'] > $this->file_max_size){
                $result['error'] = true;
                $result['message'] = '上传文件太大';
            }
            if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])){
                $name_arr = explode('.',$_FILES['file']['name']);
                $suffix = end($name_arr);
                if(strtolower($suffix) != 'apk'){
                    $result['error'] = true;
                    $result['message'] = '文件格式错误';
                }else{
                    $file_name = md5($_FILES['file']['tmp_name']).'.'.$suffix;
                    $file_path = $path.$file_name;
                    if(!move_uploaded_file($_FILES['file']['tmp_name'],$file_path)){
                        $result['error'] = true;
                        $result['message'] = '上传失败';
                    }else{
                        $result['data']['size'] = $_FILES['file']['size'];
                        $result['data']['path'] = $file_path;
                        $result['data']['apk_url'] = '/'.date('Ym').'/'.date('d').'/'.$file_name;
                    }
                }
            }
        }
        return $result;
    }

    public function uploadZip(){
        $result = ['error' => false,'message' => '','data' => []];
        $path = $this->skillstore_apk.'/'.date('Ym').'/'.date('d').'/';
        $this->createFolder($path);
        if(isset($_FILES)){
            if($_FILES['file']['error'] != 0){
                $result['error'] = true;
                $result['message'] = '上传有误';
            }
            if($_FILES['file']['size'] > $this->file_max_size){
                $result['error'] = true;
                $result['message'] = '上传文件太大';
            }
            if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])){
                $name_arr = explode('.',$_FILES['file']['name']);
                $suffix = end($name_arr);
                if(strtolower($suffix) != 'zip'){
                    $result['error'] = true;
                    $result['message'] = '文件格式错误';
                }else{
                    $file_name = md5($_FILES['file']['tmp_name']).'.'.$suffix;
                    $file_path = $path.$file_name;
                    if(!move_uploaded_file($_FILES['file']['tmp_name'],$file_path)){
                        $result['error'] = true;
                        $result['message'] = '上传失败';
                    }else{
                        $result['data']['size'] = $_FILES['file']['size'];
                        $result['data']['path'] = $file_path;
                        $result['data']['apk_url'] = '/'.date('Ym').'/'.date('d').'/'.$file_name;
                    }
                }
            }
        }
        return $result;
    }

    public function StmBin($ext){
        $result = ['error' => false,'message' => '上传成功','data' => ''];
        $path = $this->stmfile.'/';
        //print_r($_FILES);
        $this->createFolder($path);
        if(isset($_FILES)){
            if($_FILES['file']['error'][0]){
                $result['error'] = true;
                $result['message'] = '上传失败';
            }else{
                $ext_arr = explode(".",$_FILES['file']['name'][0]);
                $tmpext = end($ext_arr);
                if($tmpext!=$ext){
                    $result['error'] = true;
                    $result['message'] = '文件格式错误';
                }else{
                    $file_name = $_FILES['file']['name'][0];
                    $file_path = $path . '/' . $file_name;
                    if(!move_uploaded_file($_FILES['file']['tmp_name'][0], $file_path)){
                        $result['error'] = true;
                        $result['message'] = '文件上传失败';
                    }else{
                        $result['data'] = $file_name;
                    }
                } 
            }
        }else{
            $result['error'] = true;
            $result['message'] = '请选择文件';
        }
        return $result;
    }

    public function StmApk(){
        $result = ['error' => false,'message' => '','data' => []];
        $path = $this->stmfile.'/';
        $this->createFolder($path);
        if(isset($_FILES)){
            if($_FILES['file']['error'] != 0){
                $result['error'] = true;
                $result['message'] = '上传有误';
            }
            if($_FILES['file']['size'] > $this->file_max_size){
                $result['error'] = true;
                $result['message'] = '上传文件太大';
            }
            if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])){
                $name_arr = explode('.',$_FILES['file']['name']);
                $suffix = end($name_arr);
                if(strtolower($suffix) != 'apk'){
                    $result['error'] = true;
                    $result['message'] = '文件格式错误';
                }else{
                    $file_name = md5($_FILES['file']['tmp_name']).'.'.$suffix;
                    $file_path = $path.$file_name;
                    if(!move_uploaded_file($_FILES['file']['tmp_name'],$file_path)){
                        $result['error'] = true;
                        $result['message'] = '上传失败';
                    }else{
                        $result['data']['size'] = $_FILES['file']['size'];
                        $result['data']['path'] = $file_path;
                        $result['data']['apk_url'] = $file_name;
                    }
                }
            }
        }
        return $result;
    }
    
    private function createFolder($path) {
        if (!file_exists($path)) {
            $this->createFolder(dirname($path));
            mkdir($path);
            chmod($path, 0777);
        }
        return true;
    }
    
    /**
     * 读取文件夹列表
     * 单个文件夹，不读取文件夹下文件夹的内容
     */
    public function readFolderList($path){
        $file_arr = [];
        $path = $this->document_root.$path;
        if(is_dir($path)){
            foreach(scandir($path) as $key => $file){
                if($file == '.'|| $file == '..') continue;
                if(!is_dir($path.'/'.$file)){   //不是文件夹
                    $data['id'] = $key - 1;
                    $data['file_name'] = $file;
                    $fCont = file_exists($path.'/'.$file) ? file_get_contents($path.'/'.$file) : '';
                    $data['file_size'] = !empty($fCont) ? (Tools::showFileSize(strlen($fCont))) : 0;
                    $file_arr[] = $data;
                }
            }
        }
        return $file_arr;
    }
    
    /**
     * $path 路径
     * 获取该路径下文件夹列表
     */
    public function getFolderList($path){
        $file_arr = [];
        $path = $this->document_root.$path;
        if(is_dir($path)){
            foreach(scandir($path) as $key => $file){
                if($file == '.'|| $file == '..') continue;
                if(is_dir($path.'/'.$file)){   //是文件夹
                    $data['id'] = $key - 1;
                    $data['file_name'] = $file;
                    $file_arr[] = $data;
                }
            }
        }
        return $file_arr;
    }
    
    /**
     * 删除某一个文件
     * $path配置文件里的相对路径
     * $file_name文件名称
     */
    public function deleteFile($path,$file_name){
        $result = ['error' => false,'message' => '','data' => ''];
        $file = $this->document_root.$path.'/'.$file_name;
        if(file_exists($file)){
            unlink($file);
            $result['message'] = '删除成功';
            $result['data'] = TRUE;
        }else{
            $result['error'] = true;
            $result['message'] = '文件不存在';
        }
        return $result;
    }
    
    /**
    * 删除文件夹下面所有内容
    * 包括文件夹
    */
    public function deldir($path){
        $dir_path = $this->document_root.$path;
        $dh=opendir($dir_path);
        while($file=readdir($dh)) {
            if($file!='.' && $file!='..') {
                $fullpath=$dir_path.'/'.$file;
                if(!is_dir($fullpath)) {
                    unlink($fullpath);
                }else{
                    $this->deldir($path.'/'.$file);
                }
            }
        }
        closedir($dh);
        @rmdir($dir_path);
        return true;
    }
    
    /**
     * 创建文件夹
     */
    public function mkdirFload($path){
        $this->createFolder($this->document_root.$path);
    }
    
    /**
     * 读取文件内容
     */
    public function getContentFile($path){
        $path = $this->document_root.$path;
        $result = false;
        if(file_exists($path)){
            $result = file_get_contents($path);
        }
        return $result;
    }
    
    /**
     * 判断文件是否存在
     */
    public function fileIsRxist($path){
        $path = $this->document_root.$path;
        return file_exists($path) ? true : false;
    }
}
