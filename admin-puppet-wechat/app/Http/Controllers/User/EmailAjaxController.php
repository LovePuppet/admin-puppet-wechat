<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\ApiBaseController;
use Illuminate\Http\Request;
use App\Models\UserModels\Email;
class EmailAjaxController extends ApiBaseController {
  public function __construct(Request $request) {
    parent::__construct($request);
  }

  /**
   * 列表
   * ajax数据
   */
  public function dataList(){
    $oEmail = new Email();
    $result = $oEmail->getPageData($this->params);
    echo json_encode($result);
  }

  /**
   * 验证邮箱名是否存在
   */
  public function validEmail(){
    $email = trim($this->params['email']);
    $id = intval($this->params['id']);
    $oEmail = new Email();
    $data = [];
    if($id > 0){
      $data = $oEmail->db_get(['email'=>['=',$email],'status'=>['>=',0],'email_id'=>['!=',$id]]);
    }else{
      $data = $oEmail->db_get(['email'=>['=',$email],'status'=>['>=',0]]);
    }
    $this->data = !empty($data) ? true : false;
    $this->boot();
  }

  public function deleteData(){
    $id = intval($this->params['id']);
    $status = intval($this->params['status']);
    if($id > 0){
      $oEmail = new Email();
      if($oEmail->db_update('',$id,['status'=>$status])){
        $this->data = true;
      }else{
        $this->code = 1;
        $this->msg = '保存失败';
      }
    }else{
      $this->code = 1;
      $this->msg = '数据不存在';
    }
    $this->boot();
  }
}