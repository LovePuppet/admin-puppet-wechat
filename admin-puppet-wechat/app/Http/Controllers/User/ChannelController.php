<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserModels\Channel;
use App\Components\Tools;
use App\Components\WxQrcode;
use App\Components\ImageTools;
class ChannelController extends Controller {
    public function index() {
        return view('user/channel/index', ['admin_channel_tree_menu' => true]);
    }
    
    public function create(){
        return view('user/channel/create', ['admin_channel_tree_menu' => true]);
    }
    
    public function createSave(Request $request){
        $params = $request->all();
        $oChannel = new Channel();
        $data['channel_name'] = trim($params['channel_name']);
        $count = $oChannel->getCount() + 1;
        $scene_str = Tools::token($count);
        $qrcode['action_name'] = 'QR_LIMIT_STR_SCENE';
        $qrcode['action_info'] = ['scene'=>['scene_str'=>$scene_str]];
        $result = WxQrcode::createQrcode(json_encode($qrcode));
        $result_arr = json_decode($result,true);
        $data['scene_str'] = $scene_str;
        $data['ticket'] = isset($result_arr['ticket']) ? $result_arr['ticket'] : '';
        $data['expire_seconds'] = isset($result_arr['expire_seconds']) ? $result_arr['expire_seconds'] : 0;
        $data['url'] = isset($result_arr['url']) ? $result_arr['url'] : '';
        $image = WxQrcode::showqrcode($data['ticket']);
        $oImageTools = new ImageTools();
        $data['local_url'] = $oImageTools->saveImage($image,'qrcode');
        $data['create_time'] = time();
        if($oChannel->db_insert($data)){
            return redirect('admin/channel/list');
        }else{
            $request->session()->flash('error_msg','保存失败');
            return redirect('admin/channel/create');
        }
    }
    
    public function edit($id){
        if($id > 0){
            $oChannel = new Channel();
            $channel_data = $oChannel->db_get(['channel_id'=>['=',$id]]);
            if(!empty($channel_data)){
                return view('user/channel/edit', ['admin_channel_tree_menu' => true,'data'=>$channel_data]);
            }
        }
        abort(404);
    }
    
    public function editSave($id,Request $request){
        if($id > 0){
            $params = $request->all();
            $data['channel_name'] = trim($params['channel_name']);
            $oChannel = new Channel();
            $oChannel->db_update('',$id,$data);
            return redirect('admin/channel/list');
        }
        abort(404);
    }
    
    public function view($id){
        if($id > 0){
            $oChannel = new Channel();
            $channel_data = $oChannel->db_get(['channel_id'=>['=',$id]]);
            if(!empty($channel_data)){
                return view('user/channel/view', ['admin_channel_tree_menu' => true,'data'=>$channel_data]);
            }
        }
        abort(404);
    }
}
