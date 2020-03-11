<?php
namespace App\Http\Controllers\Material;
use App\Http\Controllers\Controller;
class FileController extends Controller{
    public function index(){
        return view('material/file/index',['material_file_tree_menu'=>true]);
    }
}