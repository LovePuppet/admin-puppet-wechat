<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserModels\Email;
class EmailController extends Controller {
  public function index() {
    return view('user/email/index', ['admin_email_tree_menu' => true]);
  }

  public function create(){
    return view('user/email/create', ['admin_email_tree_menu' => true]);
  }

  public function createSave(Request $request){
    $params = $request->all();
    $data['name'] = trim($params['name']);
    $data['email'] = trim($params['email']);
    $data['create_time'] = time();
    $oEmail = new Email();
    if($oEmail->db_insert($data)){
      return redirect('admin/email/list');
    }else{
      $request->session()->flash('error_msg','保存失败');
      return redirect('admin/email/create');
    }
  }

  public function edit($id){
    if($id > 0){
      $oEmail = new Email();
      $data = $oEmail->db_get(['email_id'=>['=',$id]]);
      if(!empty($data)){
        return view('user/email/edit', ['admin_email_tree_menu' => true,'data'=>$data]);
      }
    }
    abort(404);
  }

  public function editSave($id,Request $request){
    if($id > 0){
      $params = $request->all();
      $data['name'] = trim($params['name']);
      $data['email'] = trim($params['email']);
      $oEmail = new Email();
      $oEmail->db_update('',$id,$data);
      return redirect('admin/email/list');
    }
    abort(404);
  }
}
