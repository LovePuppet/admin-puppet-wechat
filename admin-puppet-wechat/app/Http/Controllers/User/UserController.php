<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserModels\User;
class UserController extends Controller {
  public function index() {
    $oChannel = new \App\Models\UserModels\Channel();
    $channels = $oChannel->getAllData();
    return view('user/index', ['admin_user_tree_menu' => true,'channels'=>$channels]);
  }
}
