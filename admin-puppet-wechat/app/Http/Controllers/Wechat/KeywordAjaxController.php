<?php
namespace App\Http\Controllers\Wechat;
use App\Http\Controllers\ApiBaseController;
use Illuminate\Http\Request;
use App\Models\WechatModels\Keyword;
use App\Components\Tools;
class KeywordAjaxController extends ApiBaseController{
    
    public function __construct(Request $request) {
        parent::__construct($request);
    }
    
    /**
     * 列表
     * ajax数据
     */
    public function dataList(){
        $oKeyword = new Keyword();
        $result = $oKeyword->getPageData($this->params);
        echo json_encode($result);
    }
    
    public function saveData(){
        
    }
}