<?php
namespace App\Http\Controllers\Wechat;
use App\Http\Controllers\Controller;
use App\Models\MaterialModels\Text;
use App\Models\MaterialModels\Image;
use App\Models\MaterialModels\News;
use App\Models\WechatModels\Follow;
use App\Models\MaterialModels\Video;
class FollowController extends Controller{
  public function index(){
    $oText = new Text;
    $textList = $oText->getPageData([]);
    $oImage = new Image;
    $imageList = $oImage->getPageData([]);
    $oNews = new News;
    $newsList = $oNews->getPageData([]);
    $oVideo = new Video();
    $videoList = $oVideo->getPageData([]);
    $oFollow = new Follow();
    $data = $oFollow->db_get(['type'=>['=',1],'status'=>['=',1]]);
    return view('wechat/follow/index',['admin_wechat_follow_tree_menu'=>true,'textList'=>$textList,'imageList'=>$imageList,'newsList'=>$newsList,'data'=>$data,'videoList'=>$videoList]);
  }

  public function repeat(){
    $oText = new Text;
    $textList = $oText->getPageData([]);
    $oImage = new Image;
    $imageList = $oImage->getPageData([]);
    $oNews = new News;
    $newsList = $oNews->getPageData([]);
    $oVideo = new Video();
    $videoList = $oVideo->getPageData([]);
    $oFollow = new Follow();
    $data = $oFollow->db_get(['type'=>['=',2],'status'=>['=',1]]);
    return view('wechat/follow/repeat',['admin_wechat_follow_tree_menu'=>true,'textList'=>$textList,'imageList'=>$imageList,'newsList'=>$newsList,'data'=>$data,'videoList'=>$videoList]);
  }
}