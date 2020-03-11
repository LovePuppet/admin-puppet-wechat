<?php
namespace App\Http\Controllers\Material;
use App\Http\Controllers\ApiBaseController;
use Illuminate\Http\Request;
use App\Models\MaterialModels\News;
use App\Components\WxTools;
use App\Components\ImageTools;
class NewsAjaxController extends ApiBaseController{
    
    public function __construct(Request $request) {
        parent::__construct($request);
    }
    
    /**
     * 列表
     * ajax数据
     */
    public function dataList(){
        $oNews = new News;
        $result = $oNews->getPageData($this->params);
        echo json_encode($result);
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
                $oNews = new News;
                $result = $oNews->db_get(['news_id'=>['=',$this->params['id']]]);
                if(!empty($result)){
                    if($oNews->db_update('',$this->params['id'],['status'=>$this->params['status']])){
                        WxTools::deleteWxMaterial($result['media_id']);
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
    
    public function newsSync(){
        $params = ['type'=>'news','offset'=>0,'count'=>20];
        $result = WxTools::wxMaterial($params);
        $data = json_decode($result,true);
        if(isset($data['errcode'])){
            $this->code = 1;
            $this->msg = $data['errmsg'];
        }else{
            $this->saveNews($data['item']);
            $page = ceil($data['total_count']/20);
            if($page > 1){
                for($i = 2;$i<=$page;$i++){
                    $params = ['type'=>'news','offset'=>20*($i-1),'count'=>20];
                    $result = WxTools::wxMaterial($params);
                    $data = json_decode($result,true);
                    $this->saveNews($data['item']);
                }
            }
            $this->data = true;
        }
        $this->boot();
    }
    
    public function saveNews($data){
        $result = false;
        if(!empty($data) && is_array($data)){
            $oNews = new News();
            $oImageTools = new ImageTools();
            foreach ($data as $val){
                $newsData = $oNews->db_get(['media_id'=>['=',$val['media_id']],'status'=>['=',1]]);
                if(empty($newsData)){
                    $news = [];
                    $news['media_id'] = $val['media_id'];
                    $news['content'] = json_encode($val['content']);
                    $news['url'] = '';
                    $news['title'] = '';
                    if(!empty($val['content']['news_item'])){
                        $title = [];
                        $new = [];
                        foreach ($val['content']['news_item'] as $new_val){
                            $title[]= $new_val['title'];
                            $wxData = WxTools::getWxMaterial($val['content']['news_item'][0]['thumb_media_id']);
                            $new[] = $oImageTools->saveImage($wxData);
                        }
                        $news['url'] = json_encode($new);
                        $news['title'] = json_encode($title);
                    }
                    $news['create_time'] = $val['update_time'];
                    $news['status'] = 1;
                    $oNews->db_insert($news);
                }
            }
        }
        return $result;
    }
    
    public function getData(){
        if(isset($this->params['media_id']) && !empty($this->params['media_id'])){
            $oNews = new News();
            $data = $oNews->db_get(['media_id'=>['=',$this->params['media_id']],'status'=>['=',1]]);
            if(!empty($data)){
                $data['url'] = json_decode($data['url'],true);
                $data['title'] = json_decode($data['title'],true);
                $this->data = $data;
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
}