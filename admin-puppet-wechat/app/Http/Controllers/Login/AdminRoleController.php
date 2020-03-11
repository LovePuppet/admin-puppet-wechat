<?php

namespace App\Http\Controllers\Login;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminModels\AdminRole;
class AdminRoleController extends Controller{
    /**
     * 管理员权限列表页面
     * admin_role_tree_menu
     * 后台管理员管理select是否默认展开
     */
    public function index(){
        return view('login/admin-role/index',['admin_role_tree_menu'=>true]);
    }
    
    /**
     * 新增管理员
     */
    public function create(){
        $data = \App\Components\Tools::adminLimits();
        return view('login/admin-role/create',['admin_role_tree_menu'=>true,'data'=>$data]);
    }
    
    /**
     * 新增管理员表单提交
     */
    public function createSave(Request $request){
        $parames = $request->all();
        if(isset($parames['role_name']) && !empty($parames['role_name']) && isset($parames['limits_ids']) && !empty($parames['limits_ids'])){
            $data['role_name'] = $parames['role_name'];
            $limits_ids_arr = [];
            if(strpos($parames['limits_ids'],',') === false){//不包含逗号，表示选择了一个权限
                $limits_ids_arr[] = $parames['limits_ids'];
            }else{
                $limits_ids_arr = explode(',', $parames['limits_ids']);
            }
            $data['limits_ids'] = json_encode($limits_ids_arr);
            $oAdminRole = new AdminRole;
            if($oAdminRole->db_insert($data)){
                //更新角色缓存数据
                \App\Components\Tools::adminRoles(true);
                return redirect('admin/role/list');
            }else{
                $request->session()->flash('error_msg','保存失败');
                return redirect('admin/role/create');
            }
        }else{
            $request->session()->flash('error_msg','请填写完整表单信息');
            return redirect('admin/role/create');
        }
    }
    
    /**
     * 修改管理员
     */
    public function edit($id){
        if(intval($id) > 0){
            $oAdminRole = new AdminRole;
            $data = $oAdminRole->db_get(['admin_role_id'=>['=',$id]]);
            if(!empty($data)){
                $limits = \App\Components\Tools::adminLimits();
                return view('login/admin-role/edit',['admin_role_tree_menu'=>true,'data'=>$data,'limits'=>$limits]);
            }
        }
        abort(404);
    }
    
    /**
     * 修改管理员表单提交
     */
    public function editSave($id,Request $request){
        $parames = $request->all();
        if(intval($id) > 0){
            $oAdminRole = new AdminRole;
            $result = $oAdminRole->db_get(['admin_role_id'=>['=',$id]]);
            if(!empty($result)){
                if(isset($parames['role_name']) && !empty($parames['role_name']) && isset($parames['limits_ids']) && !empty($parames['limits_ids'])){
                    $data['role_name'] = $parames['role_name'];
                    $limits_ids_arr = [];
                    if(strpos($parames['limits_ids'],',') === false){//不包含逗号，表示选择了一个权限
                        $limits_ids_arr[] = $parames['limits_ids'];
                    }else{
                        $limits_ids_arr = explode(',', $parames['limits_ids']);
                    }
                    $data['limits_ids'] = json_encode($limits_ids_arr);
                    if($oAdminRole->db_update('',$id,$data)){
                        //更新角色缓存数据
                        \App\Components\Tools::adminRoles(true);
                        return redirect('admin/role/list');
                    }else{
                        return redirect('admin/role/list');
                    }
                }
            }
        }
        abort(404);
    }
    
    public function view($id){
        if(intval($id) > 0){
            $oAdminRole = new AdminRole;
            $data = $oAdminRole->db_get(['admin_role_id'=>['=',$id]]);
            if(!empty($data)){
                $data['limit_names'] = \App\Components\Tools::getAdminLimitsNameOrUrl(json_decode($data['limits_ids'],true));
                return view('login/admin-role/view',['admin_role_tree_menu'=>true,'data'=>$data]);
            }
        }
        abort(404);
    }
}
