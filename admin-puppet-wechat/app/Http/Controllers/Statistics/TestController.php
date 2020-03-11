<?php
namespace App\Http\Controllers\Statistics;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class TestController extends Controller {
  public function test1() {
    return view('statistics/test1', ['admin_test1_tree_menu' => true]);
  }
  
  public function test2() {
    return view('statistics/test2', ['admin_test2_tree_menu' => true]);
  }
}
