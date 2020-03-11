<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
class UserInfoController extends Controller{
    public function index(){
        return view('web/userinfo');
    }
}