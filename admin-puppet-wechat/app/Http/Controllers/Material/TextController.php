<?php
namespace App\Http\Controllers\Material;
use App\Http\Controllers\Controller;
class TextController extends Controller{
    public function index(){
        return view('material/text/index',['material_text_tree_menu'=>true]);
    }
}