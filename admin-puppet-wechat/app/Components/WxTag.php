<?php
namespace App\Components;
/**
 * 微信标签
 * @author 郭钊林
 */
class WxTag{
    
    /**
     * 创建标签
     * request {   "tag" : {     "name" : "广东"//标签名   } }
     * respond {   "tag":{ "id":134,//标签id "name":"广东"   } }
     */
    public static function createTags($params){
        \XLog::info("in.params=".$params, __FILE__, __FUNCTION__, __LINE__);
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/create?access_token='.WxTools::wxAccessToken();
        return HttpTools::open($url,'POST',$params,false,true);
    }
    
    /**
     * 获取已经创建的所有标签
     * request
     * respond{   "tags":[{       "id":1,       "name":"每天一罐可乐星人",       "count":0 //此标签下粉丝数 },
     * {   "id":2,   "name":"星标组",   "count":0 },
     * {   "id":127,   "name":"广东",   "count":5 }   
     *  ]
     *  }
     */
    public static function getTags(){
        \XLog::info("in.", __FILE__, __FUNCTION__, __LINE__);
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.WxTools::wxAccessToken();
        return HttpTools::open($url,'POST',FALSE,false,true);
    }
    
    /**
     * 修改标签
     * request {   "tag" : {     "id" : 134,     "name" : "广东人"   } }
     * respond {   "errcode":0,   "errmsg":"ok" }
     */
    public static function updateTags($params){
        \XLog::info("in.params=".$params, __FILE__, __FUNCTION__, __LINE__);
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/update?access_token='.WxTools::wxAccessToken();
        return HttpTools::open($url,'POST',$params,false,true);
    }
    
    /**
     * 删除标签
     * request {   "tag":{        "id" : 134   } }
     * respond {   "errcode":0,   "errmsg":"ok" }
     */
    public static function deleteTags($params){
        \XLog::info("in.params=".$params, __FILE__, __FUNCTION__, __LINE__);
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/delete?access_token='.WxTools::wxAccessToken();
        return HttpTools::open($url,'POST',$params,false,true);
    }
    
    /**
     * 获取标签下粉丝列表
     * request {   "tagid" : 134,   "next_openid":""//第一个拉取的OPENID，不填默认从头开始拉取 }
     * respond {   "count":2,//这次获取的粉丝数量   
     *   "data":{//粉丝列表
     *   "openid":[  
     *   "ocYxcuAEy30bX0NXmGn4ypqx3tI0",    
     *   "ocYxcuBt0mRugKZ7tGAHPnUaOW7Y"  ]  
     *   },  
     *   "next_openid":"ocYxcuBt0mRugKZ7tGAHPnUaOW7Y"//拉取列表最后一个用户的openid 
     *   }
     */
    public static function getTagUser($params){
        \XLog::info("in.params=".$params, __FILE__, __FUNCTION__, __LINE__);
        $url = 'https://api.weixin.qq.com/cgi-bin/user/tag/get?access_token='.WxTools::wxAccessToken();
        return HttpTools::open($url,'POST',$params,false,true);
    }
    
    /**
     * 批量为用户打标签
     * request {   "openid_list" : [//粉丝列表    "ocYxcuAEy30bX0NXmGn4ypqx3tI0","ocYxcuBt0mRugKZ7tGAHPnUaOW7Y"],"tagid" : 134 }
     * respond {   "errcode":0,   "errmsg":"ok"}
     */
    public static function userTagsBatchtagging($params){
        \XLog::info("in.params=".$params, __FILE__, __FUNCTION__, __LINE__);
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token='.WxTools::wxAccessToken();
        return HttpTools::open($url,'POST',$params,false,true);
    }
    
    /**
     * 批量为用户取消标签
     * request {   "openid_list" : [//粉丝列表     
     *   "ocYxcuAEy30bX0NXmGn4ypqx3tI0",     
     *   "ocYxcuBt0mRugKZ7tGAHPnUaOW7Y"   ],   
     *   "tagid" : 134 }
     * respond  {   "errcode":0,   "errmsg":"ok"}
     */
    public static function userTagsBatchuntagging($params){
        \XLog::info("in.params=".$params, __FILE__, __FUNCTION__, __LINE__);
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchuntagging?access_token='.WxTools::wxAccessToken();
        return HttpTools::open($url,'POST',$params,false,true);
    }
    
    /**
     * 获取用户身上的标签列表
     * request {   "openid" : "ocYxcuBt0mRugKZ7tGAHPnUaOW7Y" }
     * respond {   "tagid_list":[//被置上的标签列表 134, 2   ] }
     */
    public static function getUserTags($params){
        \XLog::info("in.params=".$params, __FILE__, __FUNCTION__, __LINE__);
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/getidlist?access_token='.WxTools::wxAccessToken();
        return HttpTools::open($url,'POST',$params,false,true);
    }
}

