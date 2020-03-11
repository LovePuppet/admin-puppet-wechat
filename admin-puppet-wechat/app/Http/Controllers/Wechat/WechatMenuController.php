<?php
namespace App\Http\Controllers\Wechat;
use App\Http\Controllers\Controller;
use App\Models\MaterialModels\Text;
use App\Models\MaterialModels\Image;
use App\Models\MaterialModels\News;
use App\Models\MaterialModels\Video;
class WechatMenuController extends Controller{
    public function index(){
        $oText = new Text;
        $textList = $oText->getPageData([]);
        $oImage = new Image;
        $imageList = $oImage->getPageData([]);
        $oNews = new News;
        $newsList = $oNews->getPageData([]);
        $oVideo = new Video();
        $videoList = $oVideo->getPageData([]);
        return view('wechat/menu/index',['admin_wechat_menu_tree_menu'=>true,'textList'=>$textList,'imageList'=>$imageList,'newsList'=>$newsList,'videoList'=>$videoList]);
    }
}