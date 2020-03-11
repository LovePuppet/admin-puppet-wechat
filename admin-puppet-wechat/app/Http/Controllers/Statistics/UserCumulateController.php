<?php
namespace App\Http\Controllers\Statistics;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class UserCumulateController extends Controller {
  public function index() {
    return view('statistics/channel/index', ['admin_user_cumulate_tree_menu' => true]);
  }
}
