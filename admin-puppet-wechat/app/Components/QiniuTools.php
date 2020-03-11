<?php
namespace App\Components;
use Qiniu\Storage\UploadManager;
use Qiniu\Auth;
use Log;

/**
 * 七牛上传工具类
 */
class QiniuTools{
    private $ak = '7hsk9bBvCKkizDSSHribC6sqvxfGSFTqsnWO_ShW';
    private $sk = 'KPLjjq0yxovd2erLZaDXTvpa0Ixvsn6dSajiT4Ls';
    private $imageBucketName = 'abilix-storm';
    private $videoBucketName = 'abilix-storm-video';
    private $img_size = 2097152; //2M
    private $img_type_arr = ['image/gif','image/pjpeg','image/jpeg','image/png', 'image/x-png','image/bmp'];
    private $ext_arr = ['image/gif' => '.gif', 'image/pjpeg' => '.jpg', 'image/jpeg' => '.jpg', 'image/png' => '.png', 'image/x-png' => '.png'];
    /**
     * 
     * @param object $image 
     * @param int 1.web 2.二进制流 3.16进制流 
     * @return type
     */
    public function imageUpload($image,$form,$name){
        $upManager = new UploadManager();
        $auth = new Auth($this->ak, $this->sk);
        $token = $auth->uploadToken($this->imageBucketName);
        $content = file_get_contents($image);
        list($ret, $error) = $upManager->put($token,$name,$content);
        return ($error == NULL) ? $name : false;
    }
    
    public function getFormImage(){
        $result = false;
        if(isset($_FILES)){
            if($_FILES['file']['error'][0] != 0 || $_FILES['file']['size'][0] > $this->img_size || !in_array($_FILES['file']['type'][0], $this->img_type_arr)){
                $result = false;
            }else{
                $name_arr = explode('.',$_FILES['file']['name'][0]);
                $suffix = end($name_arr);
                $file_name = md5($_FILES['file']['tmp_name'][0]).'.'.$suffix;
                $result = $this->imageUpload($_FILES['file']['tmp_name'][0],1,$file_name);
            }
        }
        return $result;
    }
}

