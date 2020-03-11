<?php

namespace App\Http\Controllers\Home;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class HomeController extends Controller{
  private $params;
  public function __construct(Request $request) {
    $this->params = $request->all();
  }
    
  public function index(){
    return view('home/index');
  }
}