<?php
namespace App\Http\Controllers\Wechat;
use App\Http\Controllers\ApiBaseController;
use Illuminate\Http\Request;
use App\Models\WechatModels\WechatMenu;
use App\Components\Tools;
class WechatMenuAjaxController extends ApiBaseController{
    
  public function __construct(Request $request) {
    parent::__construct($request);
  }

  /**
   * 列表
   * ajax数据
   */
  public function dataList(){
    $oWechatMenu = new WechatMenu;
    $result = $oWechatMenu->getAjaxAllData();
    echo json_encode($result);
  }
    
  /**
   * 保存菜单方法
   */
  public function saveData(Request $request){
    if(!$request->session()->has('user')){
      $this->code = 1;
      $this->msg = '登录已过时，请重新登录';
    }else{
      if(isset($this->params['name']) && !empty($this->params['name']) && isset($this->params['sort'])){
        $oWechatMenu = new WechatMenu;
        if(isset($this->params['id']) && intval($this->params['id']) > 0){
            $data['fid'] = isset($this->params['fid']) ? intval($this->params['fid']) : 0;
            $data['name'] = trim($this->params['name']);
            $data['sort'] = intval($this->params['sort']);
            if($oWechatMenu->db_update('',intval($this->params['id']),$data)){
                $this->msg = '保存成功';
            }else{
                $this->code = 1;
                $this->msg = '保存失败';
            }
        }else{
          if(isset($this->params['fid']) && intval($this->params['fid']) > 0){
            $fcount = $oWechatMenu->getSubCount(intval($this->params['fid']));
            if($fcount >= 5){
              $this->code = 1;
              $this->msg = '二级菜单最多5条';
            }
          }else{
            $fcount = $oWechatMenu->getSubCount();
            if($fcount >= 3){
              $this->code = 1;
              $this->msg = '一级菜单最多3条';
            }
          }
          if($this->code == 0){
            $count = $oWechatMenu->getCount() + 1;
            $token = Tools::token($count);
            $data['token'] = $token;
            $data['crc_token'] = Tools::crcToken($token);
            $data['fid'] = isset($this->params['fid']) ? intval($this->params['fid']) : 0;
            $data['name'] = trim($this->params['name']);
            $data['sort'] = intval($this->params['sort']);
            $data['create_time'] = time();
            $data['status'] = 1;
            if($oWechatMenu->db_insert($data)){
              $this->msg = '保存成功';
            }else{
              $this->code = 1;
              $this->msg = '保存失败';
            }
          }
        }
      }else{
        $this->code = 1;
        $this->msg = '缺少数据';
      }
    }
    $this->boot();
  }
    
  public function checkData(Request $request){
    if(isset($this->params['id']) && intval($this->params['id']) > 0){
      $oWechatMenu = new WechatMenu;
      $result = $oWechatMenu->db_get(['menu_id'=>['=',$this->params['id']]]);
      if(!empty($result)){
        if($result['fid'] == 0){
          $count = $oWechatMenu->getSubCount($this->params['id']);
          if($count > 0){
            $this->code = 1;
            $this->msg = '已经有子菜单，不能设置回复';
          }else{
            $this->data = $result;
          }
        }else{
          $this->data = $result;
        }
      }else{
        $this->code = 1;
        $this->msg = '数据不存在';
      }
    }else{
      $this->code = 1;
      $this->msg = '缺少数据';
    }
    $this->boot();
  }
    
  /**
   * 操作状态
   * 开启/关闭/删除
   */
  public function deleteData(Request $request){
    if(!$request->session()->has('user')){
      $this->code = 1;
      $this->msg = '登录已过时，请重新登录';
    }else{
      if(isset($this->params['id']) && intval($this->params['id']) > 0 && isset($this->params['status'])){
        $oWechatMenu = new WechatMenu;
        $result = $oWechatMenu->db_get(['menu_id'=>['=',$this->params['id']]]);
        if(!empty($result)){
          if($oWechatMenu->db_update('',$this->params['id'],['status'=>$this->params['status']])){
            $this->data = true;
          }else{
            $this->code = 1;
            $this->msg = '操作失败';
          }
        }else{
          $this->code = 1;
          $this->msg = '数据不存在';
        }
      }else{
        $this->code = 1;
        $this->msg = '缺少数据';
      }
    }
    $this->boot();
  }
    
  //加载更多
  public function materialList(){
    if(isset($this->params['type']) && intval($this->params['type']) > 0 && isset($this->params['page']) && intval($this->params['page']) > 0){
      switch (intval($this->params['type'])){
        case 1:
          $oText = new \App\Models\MaterialModels\Text();
          $data = $oText->getPageData(['page'=>$this->params['page']]);
          $this->data['data'] = $data['data'];
          $this->data['page'] = ceil($data['total']/10);
          break;
        case 2:
          $oImage = new \App\Models\MaterialModels\Image();
          $data = $oImage->getPageData(['page'=>$this->params['page']]);
          $this->data['data'] = $data['data'];
          $this->data['page'] = ceil($data['total']/10);
          break;
        case 3:
          $oNews = new \App\Models\MaterialModels\News();
          $data = $oNews->getPageData(['page'=>$this->params['page']]);
          $this->data['data'] = $data['data'];
          $this->data['page'] = ceil($data['total']/10);
          break;
        case 6:
          $oVideo = new \App\Models\MaterialModels\Video();
          $data = $oVideo->getPageData(['page'=>$this->params['page']]);
          $this->data['data'] = $data['data'];
          $this->data['page'] = ceil($data['total']/10);
          break;
      }
    }else{
      $this->code = 1;
      $this->msg = '缺少数据';
    }
    $this->boot();
  }
    
  /**
   * 保存设置的素材
   */
  public function saveMaterial(){
    if(isset($this->params['menu_id']) && intval($this->params['menu_id']) > 0 && isset($this->params['type']) && !empty($this->params['type'])){
      $oWechatMenu = new WechatMenu;
      if($this->params['content'] == 'PuppetRobot'){  //如果选择了机器人
        $this->params['crc_token'] = 'PuppetRobot';
      }else{  //如果没选择了机器人
        $token = Tools::token(intval($this->params['menu_id']));
        $this->params['crc_token'] = Tools::crcToken($token);
      }
      if($oWechatMenu->db_update('',$this->params['menu_id'],$this->params)){
        $this->data = true;
      }else{
        $this->code = 1;
        $this->msg = '保存失败';
      }
    }else{
        $this->code = 1;
        $this->msg = '缺少数据';
    }
    $this->boot();
  }
    
  public function publishMenu(){
    $oWechatMenu = new WechatMenu;
    $result = $oWechatMenu->getAllData();
    $menu = [];
    if(!empty($result)){
      foreach ($result as $val){
        $smenu = [];
        if(!empty($val['sub'])){
          $smenu["name"] = $val['name'];
          $sub_menu = [];
          foreach ($val['sub'] as $sub){
            $sub_menu[] = $this->getVal($sub);
          }
          $smenu['sub_button'] = $sub_menu;
        }else{
          $smenu = $this->getVal($val);
        }
        $menu[] = $smenu;
      }
    }
    if(!empty($menu)){
      $result = \App\Components\WxTools::pulishMenu($menu);
      $data = json_decode($result,true);
      if($data['errcode'] == 0){
          $this->msg = '发布成功';
      }else{
        $this->code = 1;
        $this->msg = $data['errmsg'];
      }
    }else{
      $this->code = 1;
      $this->msg = '数据不能为空';
    }
    $this->boot();
  }
    
  private function getVal($data){
    $result = [];
    $result["name"] = $data['name'];
    $result["type"] = $data['type'];
    switch ($data['type']){
      case 'click':
          $result["key"] = $data['content'];
          break;
      case 'view':
          $result["url"] = $data['url'];
          break;
      case 'media_id':
          $result["media_id"] = $data['media_id'];
          break;
      case 'miniprogram':
        $result["url"] = $data['url'];
        $result["appid"] = $data['appid'];
        $result["pagepath"] = $data['pagepath'];
        break;
    }
    return $result;
  }
}

