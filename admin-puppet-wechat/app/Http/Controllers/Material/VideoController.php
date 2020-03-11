<?php
namespace App\Http\Controllers\Material;
use App\Http\Controllers\Controller;
class VideoController extends Controller{
  public function index(){
    return view('material/video/index',['material_video_tree_menu'=>true]);
  }
}