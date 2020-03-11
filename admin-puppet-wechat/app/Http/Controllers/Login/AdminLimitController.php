<?php

namespace App\Http\Controllers\Login;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminModels\AdminLimit;
class AdminLimitController extends Controller{
    
    /**
     * 管理员权限列表页面
     * [0,1,1]
     */
    public function index(){
        return view('login/admin-limit/index',['admin_limit_tree_menu'=>TRUE]);
    }
    
    /**
     * 新增管理员
     */
    public function create(){
        return view('login/admin-limit/create',['admin_limit_tree_menu'=>TRUE]);
    }
    
    /**
     * 新增管理员表单提交
     */
    public function createSave(Request $request){
        $parames = $request->all();
        if(isset($parames['limit_name']) && !empty($parames['limit_name']) && isset($parames['limit_url']) && !empty($parames['limit_url'])){
            $data['limit_name'] = $parames['limit_name'];
            $data['limit_url'] = $parames['limit_url'];
            $oAdminLimit = new AdminLimit;
            if($oAdminLimit->db_insert($data)){
                //更新权限缓存数据
                \App\Components\Tools::adminLimits(true);
                return redirect('admin/limit/list');
            }else{
                $request->session()->flash('error_msg','保存失败');
                return redirect('admin/limit/create');
            }
        }else{
            $request->session()->flash('error_msg','请填写完整表单信息');
            return redirect('admin/limit/create');
        }
    }
    
    /**
     * 修改管理员
     */
    public function edit($id){
        if(intval($id) > 0){
            $oAdminLimit = new AdminLimit;
            $data = $oAdminLimit->db_get(['admin_limit_id'=>['=',$id]]);
            if(!empty($data)){
                return view('login/admin-limit/edit',['admin_limit_tree_menu'=>TRUE,'data'=>$data]);
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
            $oAdminLimit = new AdminLimit;
            $result = $oAdminLimit->db_get(['admin_limit_id'=>['=',$id]]);
            if(!empty($result)){
                if(isset($parames['limit_name']) && !empty($parames['limit_name']) && isset($parames['limit_url']) && !empty($parames['limit_url'])){
                    $data['limit_name'] = $parames['limit_name'];
                    $data['limit_url'] = $parames['limit_url'];
                    if($oAdminLimit->db_update('',$id,$data)){
                        //更新权限缓存数据
                        \App\Components\Tools::adminLimits(true);
                        return redirect('admin/limit/list');
                    }else{
                        return redirect('admin/limit/list');
                    }
                }
            }
        }
        abort(404);
    }
    
    public function view($id){
        if(intval($id) > 0){
            $oAdminLimit = new AdminLimit;
            $data = $oAdminLimit->db_get(['admin_limit_id'=>['=',$id]]);
            if(!empty($data)){
                return view('login/admin-limit/view',['admin_limit_tree_menu'=>TRUE,'data'=>$data]);
            }
        }
        abort(404);
    }
}
