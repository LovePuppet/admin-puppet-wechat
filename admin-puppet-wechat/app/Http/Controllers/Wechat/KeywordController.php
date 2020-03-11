<?php
namespace App\Http\Controllers\Wechat;
use App\Http\Controllers\Controller;
class KeywordController extends Controller{
    public function index(){
        return view('wechat/keyword/index',['admin_wechat_keyword_tree_menu'=>true]);
    }
    
    public function create(){
        return view('wechat/keyword/create', ['admin_wechat_keyword_tree_menu' => true]);
    }
}