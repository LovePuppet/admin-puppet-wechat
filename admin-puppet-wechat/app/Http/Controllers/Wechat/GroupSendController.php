<?php
namespace App\Http\Controllers\Wechat;
use App\Http\Controllers\Controller;
use App\Models\MaterialModels\Text;
use App\Models\MaterialModels\Image;
use App\Models\MaterialModels\News;
use App\Models\MaterialModels\Video;
class GroupSendController extends Controller{
  public function index(){
    return view('wechat/group-send/index',['admin_group_send_tree_menu'=>true]);
  }

  public function create(){
    $oText = new Text;
    $textList = $oText->getPageData([]);
    $oImage = new Image;
    $imageList = $oImage->getPageData([]);
    $oNews = new News;
    $newsList = $oNews->getPageData([]);
    $oVideo = new Video();
    $videoList = $oVideo->getPageData([]);
    //所有标签
    $oTag = new \App\Models\UserModels\Tag();
    $tagList = $oTag->getAllData();
    return view('wechat/group-send/create', ['admin_group_send_tree_menu' => true,
                'textList'=>$textList,'imageList'=>$imageList,'newsList'=>$newsList,'tagList'=>$tagList,'videoList'=>$videoList]);
  }
}
