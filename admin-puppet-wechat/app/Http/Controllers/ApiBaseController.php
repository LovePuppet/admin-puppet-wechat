<?php
/** 
 * stm api接口类
 * @author puppet 郭钊林
 */
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
abstract class ApiBaseController extends Controller{
    protected $code = 0;
    protected $msg = '';
    protected $data;
    protected $params;
    
    protected function __construct(Request $request) {
        if(!$request->session()->has('user')){
            echo '请先登录';
            exit;
        }
        $this->params = $request->all();
        \XLog::info("in.params=".json_encode($this->params,JSON_UNESCAPED_UNICODE), __FILE__, __FUNCTION__, __LINE__);
    }
    
    protected function boot(){
        \XLog::info("out.code=".$this->code.", msg=".$this->msg.", data=".json_encode($this->data), __FILE__, __FUNCTION__, __LINE__);
        echo json_encode(['code'=>$this->code,'msg'=>$this->msg,'data'=>$this->data]);
    }
}

