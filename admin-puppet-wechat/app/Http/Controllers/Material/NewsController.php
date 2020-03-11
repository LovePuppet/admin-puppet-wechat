<?php
namespace App\Http\Controllers\Material;
use App\Http\Controllers\Controller;
class NewsController extends Controller{
    public function index(){
        return view('material/news/index',['material_news_tree_menu'=>true]);
    }
}