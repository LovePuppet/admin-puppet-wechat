<?php
namespace App\Http\Controllers\Robot;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RobotModels\Robot;
use App\Models\MaterialModels\Text;
use App\Models\MaterialModels\Image;
use App\Models\MaterialModels\News;
use App\Models\MaterialModels\Video;
class RobotController extends Controller{
    
    /**
     * 列表页面
     */
    public function index(Request $request){
        $params = $request->all();
        $fid = isset($params['fid']) ? intval($params['fid']) : 0;
        $prev_id = 0;
        if($fid > 0){
            $oRobot = new Robot();
            $data = $oRobot->db_get(['robot_id'=>['=',$fid],'status'=>['=',1]]);
            !empty($data) && $prev_id = $data['fid'];
        }
        return view('robot/index',['admin_robot_tree_menu'=>TRUE,'fid'=>$fid,'prev_id'=>$prev_id]);
    }
    
    /**
     * 新增
     */
    public function create(Request $request){
        $params = $request->all();
        $fid = isset($params['fid']) ? intval($params['fid']) : 0;
        $oText = new Text;
        $textList = $oText->getPageData([]);
        $oImage = new Image;
        $imageList = $oImage->getPageData([]);
        $oNews = new News;
        $newsList = $oNews->getPageData([]);
        $oVideo = new Video();
        $videoList = $oVideo->getPageData([]);
        return view('robot/create',['admin_robot_tree_menu'=>TRUE,'fid'=>$fid,'textList'=>$textList,'imageList'=>$imageList,'newsList'=>$newsList,'videoList'=>$videoList]);
    }
    
    /**
     * 修改
     */
    public function edit($id,Request $request){
      $params = $request->all();
      $fid = isset($params['fid']) ? intval($params['fid']) : 0;
      if(intval($id) > 0){
        $oRobot = new Robot;
        $data = $oRobot->db_get(['robot_id'=>['=',$id],'status'=>['=',1]]);
        if(!empty($data)){
          if(!empty($data['keyword'])){
            $keyword = json_decode($data['keyword'],true);
            $data['keyword'] = implode(' ', $keyword);
          }
          $oText = new Text;
          $textList = $oText->getPageData([]);
          $oImage = new Image;
          $imageList = $oImage->getPageData([]);
          $oNews = new News;
          $newsList = $oNews->getPageData([]);
          $oVideo = new Video();
          $videoList = $oVideo->getPageData([]);
          return view('robot/edit',['admin_robot_tree_menu'=>TRUE,'data'=>$data,'fid'=>$fid,'textList'=>$textList,'imageList'=>$imageList,'newsList'=>$newsList,'videoList'=>$videoList]);
        }
      }
      abort(404);
    }
}