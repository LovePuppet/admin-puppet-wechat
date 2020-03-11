<?php
namespace App\Http\Controllers\Material;
use App\Http\Controllers\Controller;
class ImageController extends Controller{
    public function index(){
        return view('material/image/index',['material_image_tree_menu'=>true]);
    }
}