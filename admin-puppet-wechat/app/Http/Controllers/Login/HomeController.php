<?php

namespace App\Http\Controllers\Login;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminModels\Admin;
class HomeController extends Controller{
    
    /**
     * 登录页面
     */
    public function index(){
        return view('login/home/index');
    }
    
    /**
     * 提交登录
     */
    public function login(Request $request){
        $parames = $request->all();
        if(isset($parames['username']) && !empty($parames['username']) && isset($parames['password']) && !empty($parames['password'])){
            $oAdmin = new Admin;
            $result = $oAdmin->validAdminLogin($parames['username'], $parames['password']);
            if(!empty($result)){
                $userinfo = ['admin_id'=>$result['admin_id'],'admin_name'=>$result['admin_name'],'real_name'=>$result['real_name'],'head_img'=>$result['head_img'],'power'=>$result['power'],'role_id'=>$result['role_id']];
                $request->session()->push('user', $userinfo);
                $userLimits = $oAdmin->getUserLimitInfo($result['role_id'],'limit_url');
                if($request->session()->has('userLimits')){//如果存在，先删除
                    $request->session()->forget('userLimits');
                }
                $request->session()->push('userLimits', $userLimits);
                return redirect('/');
            }
        }
        return redirect('admin/login');
    }
    
    /**
     * 注销登录
     */
    public function logoff(Request $request){
        if($request->session()->has('user')){
            $request->session()->forget('user');
        }
        return redirect('admin/login');
    }
    
    /**
     * 修改密码页面
     */
    public function updatePassword(){
        return view('login/home/update_password');
    }
    
    /**
     * 提交修改密码
     */
    public function updatePasswordForm(Request $request){
        $parames = $request->all();
        if($request->session()->has('user')){
            if(isset($parames['password']) && !empty($parames['password']) && isset($parames['new_password']) && !empty($parames['new_password']) && isset($parames['repassword']) && !empty($parames['repassword'])){
                $oAdmin = new Admin;
                $userinfo = $request->session()->get('user');
                $result = $oAdmin->validAdminLogin($userinfo[0]['admin_name'], $parames['password']);
                if(!empty($result)){
                    if($oAdmin->db_update('',$result['admin_id'],['password'=> \App\Components\Tools::passwordEncryption($parames['new_password'])])){
                        //清空session
                        $request->session()->forget('user');
                        return redirect('admin/login');
                    }else{
                        return redirect('admin/update/password');
                    }
                }
            }else{
                $request->session()->flash('error_msg','请填写完整表单信息');
                return redirect('admin/update/password');
            }
        }else{
            $request->session()->flash('error_msg','登录超时，请重新登录');
            return redirect('admin/login');
        }
    }
}
