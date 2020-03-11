<?php

namespace App\Http\Controllers\Login;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminModels\Admin;
use App\Models\AdminModels\AdminRole;
class AdminController extends Controller{
    /**
     * 管理员列表页面
     * admin_tree_menu
     * 后台管理员管理select是否默认展开
     */
    public function index(){
        return view('login/admin/index',['admin_tree_menu'=>true]);
    }
    
    /**
     * 新增管理员
     */
    public function create(){
        $data = \App\Components\Tools::adminRoles();
        return view('login/admin/create',['admin_tree_menu'=>true,'data'=>$data]);
    }
    
    /**
     * 新增管理员表单提交
     */
    public function createSave(Request $request){
        $parames = $request->all();
        if(isset($parames['username']) && !empty($parames['username']) && isset($parames['password']) && !empty($parames['password'])){
            $data['admin_name'] = $parames['username'];
            $data['password'] = \App\Components\Tools::passwordEncryption($parames['password']);
            $data['real_name'] = $parames['realname'];
            $data['mobile'] = $parames['mobile'];
            $data['role_id'] = $parames['role_id'];
            $data['create_time'] = time();
            $data['update_time'] = time();
            $data['last_login_time'] = time();
            $oAdmin = new Admin;
            if($oAdmin->db_insert($data)){
                return redirect('admin/list');
            }else{
                $request->session()->flash('error_msg','保存失败');
                return redirect('admin/create');
            }
        }else{
            $request->session()->flash('error_msg','请填写完整表单信息');
            return redirect('admin/create');
        }
    }
    
    /**
     * 修改管理员
     */
    public function edit($id){
        if(intval($id) > 0){
            $oAdmin = new Admin;
            $data = $oAdmin->getData($id);
            if(!empty($data)){
                $roles = \App\Components\Tools::adminRoles();
                return view('login/admin/edit',['admin_tree_menu'=>true,'data'=>$data,'roles'=>$roles]);
            }
        }
        abort(404);
//        return redirect('errors/404');
    }
    
    /**
     * 修改管理员表单提交
     */
    public function editSave($id,Request $request){
        $parames = $request->all();
        if(intval($id) > 0){
            $oAdmin = new Admin;
            $admininfo = $oAdmin->getData($id);
            if(!empty($admininfo)){
                if(isset($parames['username']) && !empty($parames['username']) && isset($parames['password']) && !empty($parames['password'])){
                    $data['admin_name'] = $parames['username'];
                    if($admininfo['password'] != $parames['password']){
                        $data['password'] = \App\Components\Tools::passwordEncryption($parames['password']);
                    }
                    $data['real_name'] = $parames['realname'];
                    $data['mobile'] = $parames['mobile'];
                    $data['role_id'] = $parames['role_id'];
                    $data['update_time'] = time();
                    if($oAdmin->db_update('',$id,$data)){
                        return redirect('admin/list');
                    }else{
                        return redirect('admin/list');
                    }
                }
            }
        }
        abort(404);
    }
    
    public function view($id){
        if(intval($id) > 0){
            $oAdmin = new Admin;
            $data = $oAdmin->getData($id);
            if(!empty($data)){
                $data['role_name'] = '';
                if($data['role_id']){
                    $oAdminRole = new AdminRole;
                    $roleinfo = $oAdminRole->db_get(['admin_role_id'=>['=',$data['role_id']]]);
                    !empty($roleinfo) && $data['role_name'] = $roleinfo['role_name'];
                }
                return view('login/admin/view',['admin_tree_menu'=>true,'data'=>$data]);
            }
        }
        abort(404);
    }
}
