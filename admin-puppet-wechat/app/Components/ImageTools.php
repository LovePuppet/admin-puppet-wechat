<?php
namespace App\Components;
/**
 * 图片上传类
 * @author 郭钊林
 */
class ImageTools{
    public $document_root = '';//根路径
    public $img_size = 2097152; //2M
    public $img_type_arr = ['image/gif','image/pjpeg','image/jpeg','image/png', 'image/x-png','image/bmp'];
    public $ext_arr = ['image/gif' => '.gif', 'image/pjpeg' => '.jpg', 'image/jpeg' => '.jpg', 'image/png' => '.png', 'image/x-png' => '.png'];
    
    public function __construct() {
        $this->document_root = $_SERVER['DOCUMENT_ROOT'].'/../../'.'puppet-file';
    }
    
    /**
     * 封装所有的单个文件上传方法
     * $path 文件路径
     * $ext 后缀名
     * $change_file_name 是否改变源文件名称
     * $dir_date 是否需要根据日期创建路径文件夹
     * 前端file是一个array
     */
    public function uploadSim($path,$change_file_name = false,$dir_date = false){
        $result = ['error' => false,'message' => '','data' => []];
        $path = $dir_date ? ($this->document_root.$path.'/'.date('Ym').'/'.date('d').'/') : ($this->document_root.$path);
        $this->createFolder($path);
        if(isset($_FILES)){
            if($_FILES['file']['error'][0] != 0){
                $result['error'] = true;
                $result['message'] = '上传失败';
            }elseif($_FILES['file']['size'][0] > $this->img_size){
                $result['error'] = true;
                $result['message'] = '图片大小不能大于2M';
            }elseif(!in_array($_FILES['file']['type'][0], $this->img_type_arr)){
                $result['error'] = true;
                $result['message'] = '不支持的图片格式';
            }else{
                $name_arr = explode('.',$_FILES['file']['name'][0]);
                $suffix = end($name_arr);
                $file_name = $change_file_name ? (md5($_FILES['file']['tmp_name'][0]).'.'.$suffix) : $_FILES['file']['name'][0];
                $file_path = $path.$file_name;
                if(!move_uploaded_file($_FILES['file']['tmp_name'][0],$file_path)){
                    $result['error'] = true;
                    $result['message'] = '上传失败';
                }else{
                    $result['data'] = $dir_date ? ('/'.date('Ym').'/'.date('d').'/'.$file_name) : $file_name;
                }
            }
        }else{
            $result['error'] = true;
            $result['message'] = '请选择图片';
        }
        return $result;
    }
    
    /**
     * 封装所有的单个文件上传方法
     * $path 文件路径
     * $ext 后缀名
     * $change_file_name 是否改变源文件名称
     * $dir_date 是否需要根据日期创建路径文件夹
     * 前端file是一个单个文件
     */
    public function uploadSim_($path,$change_file_name = false,$dir_date = false){
        $result = ['error' => false,'message' => '','data' => []];
        $path = $dir_date ? ($this->document_root.$path.'/'.date('Ym').'/'.date('d').'/') : ($this->document_root.$path);
        $this->createFolder($path);
        if(isset($_FILES)){
            if($_FILES['file']['error'] != 0){
                $result['error'] = true;
                $result['message'] = '上传失败';
            }elseif($_FILES['file']['size'] > $this->img_size){
                $result['error'] = true;
                $result['message'] = '图片大小不能大于2M';
            }elseif(!in_array($_FILES['file']['type'], $this->img_type_arr)){
                $result['error'] = true;
                $result['message'] = '不支持的图片格式';
            }else{
                $name_arr = explode('.',$_FILES['file']['name']);
                $suffix = end($name_arr);
                $file_name = $change_file_name ? (md5($_FILES['file']['tmp_name']).'.'.$suffix) : $_FILES['file']['name'];
                $file_path = $path.$file_name;
                if(!move_uploaded_file($_FILES['file']['tmp_name'],$file_path)){
                    $result['error'] = true;
                    $result['message'] = '上传失败';
                }else{
                    $result['data'] = $dir_date ? ('/'.date('Ym').'/'.date('d').'/'.$file_name) : $file_name;
                }
            }
        }else{
            $result['error'] = true;
            $result['message'] = '请选择图片';
        }
        return $result;
    }
    
    public function index(){
        $result = ['error' => false,'message' => '','data' => []];
        $path = $this->appstore_snapshot.'/'.date('Ym').'/'.date('d').'/';
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
                    $size > $this->img_size && $file_size = true;
                }
                if($file_size){
                    $result['error'] = true;
                    $result['message'] = '图片大小不能大于2M';
                }else{
                    $file_type = false;
                    foreach ($_FILES['file']['type'] as $type){
                        !in_array($type, $this->img_type_arr) && $file_type = true;
                    }
                    if($file_type){
                        $result['error'] = true;
                        $result['message'] = '不支持的图片格式';
                    }else{
                        $data = [];
                        $file_tmp_name = false;
                        foreach ($_FILES['file']['tmp_name'] as $key => $tmp_name){
                            $file_name = $this->ext_arr[$_FILES['file']['type'][$key]];
                            $file_name = md5($tmp_name).$file_name;
                            $file_path = $path . '/' . $file_name;
                            $file_paths['size'] = $_FILES['file']['size'][$key];
                            $file_paths['path'] = '/'.date('Ym').'/'.date('d').'/'.$file_name;
                            if(!move_uploaded_file($tmp_name, $file_path)){
                                $file_tmp_name = true;
                            }
                            $data[] = $file_paths;
                        }
                        if($file_tmp_name){
                            $result['error'] = true;
                            $result['message'] = '图片上传失败';
                            $result['data'] = [];
                        }else{
                            $result['data'] = $data;
                        }
                    }
                }
            }
        }else{
            $result['error'] = true;
            $result['message'] = '请选择图片';
        }
        return $result;
    }
    
    public function deleteImg($snapshot){
        $result = ['error' => false,'message' => '','data' => []];
        $file_path = $this->appstore_snapshot.$snapshot;
        if(file_exists($file_path)){
            unlink($file_path);
        }else{
            $result['error'] = true;
            $result['message'] = '文件不存在';
        }
        return $result;
    }
    
    /**
     * $path_key int 路径key  比如skillstore_icon路径参数为skillstore_icon
     * @return string
     */
    public function imageUpload($path_key = 'skillstore_icon'){
        $result = ['error' => false,'message' => '','data' => ''];
        $path_val = isset($this->$path_key) ? $this->$path_key : false;
        if(!$path_val){
            $result['error'] = true;
            $result['message'] = '路径不存在';
            return $result;
        }
        $path = $path_val.'/'.date('Ym').'/'.date('d').'/';
        $this->createFolder($path);
        if(isset($_FILES)){
            if($_FILES['file']['error'][0]){
                $result['error'] = true;
                $result['message'] = '上传失败';
            }else{
                if($_FILES['file']['size'][0] > $this->img_size){
                    $result['error'] = true;
                    $result['message'] = '图片大小不能大于2M';
                }else{
                    if(!in_array($_FILES['file']['type'][0], $this->img_type_arr)){
                        $result['error'] = true;
                        $result['message'] = '不支持的图片格式';
                    }else{
                        $file_name = $this->ext_arr[$_FILES['file']['type'][0]];
                        $file_name = md5($_FILES['file']['tmp_name'][0]).$file_name;
                        $file_path = $path . '/' . $file_name;
                        if(!move_uploaded_file($_FILES['file']['tmp_name'][0], $file_path)){
                            $result['error'] = true;
                            $result['message'] = '图片上传失败';
                        }else{
                            $result['data'] = '/'.date('Ym').'/'.date('d').'/'.$file_name;
                        }
                    }
                }
            }
        }else{
            $result['error'] = true;
            $result['message'] = '请选择图片';
        }
        return $result;
    }
    
    public function iosAppIcon(){
        $result = ['error' => false,'message' => '','data' => ''];
        $path = $this->appstore_icon.'/'.date('Ym').'/'.date('d').'/';
        $this->createFolder($path);
        if(isset($_FILES)){
            if($_FILES['file']['error'][0]){
                $result['error'] = true;
                $result['message'] = '上传失败';
            }else{
                if($_FILES['file']['size'][0] > $this->img_size){
                    $result['error'] = true;
                    $result['message'] = '图片大小不能大于2M';
                }else{
                    if(!in_array($_FILES['file']['type'][0], $this->img_type_arr)){
                        $result['error'] = true;
                        $result['message'] = '不支持的图片格式';
                    }else{
                        $file_name = $this->ext_arr[$_FILES['file']['type'][0]];
                        $file_name = md5($_FILES['file']['tmp_name'][0]).$file_name;
                        $file_path = $path . '/' . $file_name;
                        if(!move_uploaded_file($_FILES['file']['tmp_name'][0], $file_path)){
                            $result['error'] = true;
                            $result['message'] = '图片上传失败';
                        }else{
                            $result['data'] = '/'.date('Ym').'/'.date('d').'/'.$file_name;
                        }
                    }
                }
            }
        }else{
            $result['error'] = true;
            $result['message'] = '请选择图片';
        }
        return $result;
    }
    
    public function banner(){
        $result = ['error' => false,'message' => '','data' => ''];
        $path = $this->banner.'/'.date('Ym').'/'.date('d').'/';
        $this->createFolder($path);
        if(isset($_FILES)){
            if($_FILES['file']['error'][0]){
                $result['error'] = true;
                $result['message'] = '上传失败';
            }else{
                if($_FILES['file']['size'][0] > $this->img_size){
                    $result['error'] = true;
                    $result['message'] = '图片大小不能大于2M';
                }else{
                    if(!in_array($_FILES['file']['type'][0], $this->img_type_arr)){
                        $result['error'] = true;
                        $result['message'] = '不支持的图片格式';
                    }else{
                        $file_name = $this->ext_arr[$_FILES['file']['type'][0]];
                        $file_name = md5($_FILES['file']['tmp_name'][0]).$file_name;
                        $file_path = $path . '/' . $file_name;
                        if(!move_uploaded_file($_FILES['file']['tmp_name'][0], $file_path)){
                            $result['error'] = true;
                            $result['message'] = '图片上传失败';
                        }else{
                            $result['data'] = '/'.date('Ym').'/'.date('d').'/'.$file_name;
                        }
                    }
                }
            }
        }else{
            $result['error'] = true;
            $result['message'] = '请选择图片';
        }
        return $result;
    }
    
    /**
     * get user head img (download wx img to local)
     */
    public function getImage($imageUrl = ''){
        \XLog::info("in.imageUrl=".$imageUrl, __FILE__, __FUNCTION__, __LINE__);
        $strRet = '';
        $addr = $this->document_root.'/material-image/';
        $this->createFolder($addr);
        if($imageUrl){
            $path = '/material-image/'.md5($imageUrl).'.jpg';
            $strHeadImg = $this->document_root.$path;
            if(file_exists($strHeadImg)){
                $strRet = $path;
            }else{
                $ch=curl_init();
                $timeout=5;
                curl_setopt($ch,CURLOPT_URL, $imageUrl);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
                $data=curl_exec($ch);
                curl_close($ch);
                if(!empty($data)){
                    //创建并写入数据流，然后保存文件
                    $file = fopen($strHeadImg, "w"); //打开文件准备写入
                    fwrite($file, $data); //写入
                    fclose($file);
                    $strRet = $path;
                }
            }
        }
        \XLog::info("out.strRet=".$strRet, __FILE__, __FUNCTION__, __LINE__);
        return $strRet;
    }
    
    /**
     * 保存素材图片
     * 保存二维码图片
     */
    public function saveImage($image = '',$path = 'material-image'){
        \XLog::info("in.", __FILE__, __FUNCTION__, __LINE__);
        $strRet = '';
        $addr = $this->document_root.'/'.$path.'/';
        $this->createFolder($addr);
        if(!empty($image)){
            $path = '/'.$path.'/'.md5($image).'.jpg';
            $strHeadImg = $this->document_root.$path;
            $file = fopen($strHeadImg, "w"); //打开文件准备写入
            fwrite($file, $image); //写入
            fclose($file);
            $strRet = $path;
        }
        \XLog::info("out.strRet=".$strRet, __FILE__, __FUNCTION__, __LINE__);
        return $strRet;
    }
    
    private function createFolder($path) {
        if (!file_exists($path)) {
            $this->createFolder(dirname($path));
            mkdir($path);
            chmod($path, 0777);
        }
    }
}
