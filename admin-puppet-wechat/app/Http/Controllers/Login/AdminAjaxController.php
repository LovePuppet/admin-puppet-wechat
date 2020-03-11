<?php

namespace App\Http\Controllers\Login;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminModels\Admin;
class AdminAjaxController extends Controller{
    private $code = false;
    private $parames;
    private $message = '';
    private $data = '';
    
    public function __construct(Request $request){
        $this->parames = $request->all();
    }
    
    /**
     * 登录验证
     */
    public function login(){
        if(isset($this->parames['username']) && !empty($this->parames['username']) && isset($this->parames['password']) && !empty($this->parames['password'])){
            $oAdmin = new Admin;
            $result = $oAdmin->validAdminLogin($this->parames['username'], $this->parames['password']);
            if(!empty($result)){
                $this->data = true;
            }else{
                $this->code = true;
                $this->message = '账号或密码错误';
            }
        }else{
            $this->code = true;
            $this->message = '账号密码不能为空';
        }
        echo json_encode(['code'=>$this->code,'message'=>$this->message,'data'=>$this->data]);
    }
    
    /**
     * 修改密码验证
     */
    public function updatePassword(Request $request){
        if(!$request->session()->has('user')){
            $this->code = true;
            $this->message = '登录已过时，请重新登录';
        }else{
            if(isset($this->parames['password']) && !empty($this->parames['password'])){
                $oAdmin = new Admin;
                $result = $oAdmin->validAdminLogin($request->session()->get('user')[0]['admin_name'], $this->parames['password']);
                if(!empty($result)){
                    $this->data = true;
                }else{
                    $this->code = true;
                    $this->message = '原密码错误';
                }
            }else{
                $this->code = true;
                $this->message = '原密码不能为空';
            }
        }
        echo json_encode(['code'=>$this->code,'message'=>$this->message,'data'=>$this->data]);
    }
    
    /**
     * 管理员列表
     * ajax数据
     */
    public function adminList(){
        $oAdmin = new Admin;
        $result = $oAdmin->getPageData($this->parames);
        echo json_encode($result);
    }
    
    /**
     * 验证账号是否存在
     */
    public function validAdminName(){
        if(isset($this->parames['username']) && !empty($this->parames['username'])){
            $oAdmin = new Admin;
            $result = $oAdmin->getAdminData($this->parames['username']);
            if(!empty($result)){
                $this->code = true;
                $this->message = '账号已经存在';
            }else{
                $this->data = true;
            }
        }else{
            $this->code = true;
            $this->message = '缺少数据';
        }
        echo json_encode(['code'=>$this->code,'message'=>$this->message,'data'=>$this->data]);
    }
    
    /**
     * 操作用户状态
     * 开启/关闭
     */
    public function actionAdmin(Request $request){
        if(!$request->session()->has('user')){
            $this->code = true;
            $this->message = '登录已过时，请重新登录';
        }else{
            $url = $_SERVER['REQUEST_URI'];
            $result = \App\Components\Tools::urlLimit($url,$request);
            if(!$result){
                $this->code = true;
                $this->message = '无权限操作';
            }else{
                if(isset($this->parames['id']) && intval($this->parames['id']) > 0 && isset($this->parames['status'])){
                    $oAdmin = new Admin;
                    $result = $oAdmin->getData($this->parames['id']);
                    if(!empty($result)){
                        if($oAdmin->db_update('',$this->parames['id'],['status'=>$this->parames['status']])){
                            $this->data = true;
                        }else{
                            $this->code = true;
                            $this->message = '操作失败';
                        }
                    }else{
                        $this->code = true;
                        $this->message = '账号不存在';
                    }
                }else{
                    $this->code = true;
                    $this->message = '缺少数据';
                }
            }
        }
        echo json_encode(['code'=>$this->code,'message'=>$this->message,'data'=>$this->data]);
    }
}