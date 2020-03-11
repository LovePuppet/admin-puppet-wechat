<?php
namespace App\Models\WechatModels;
use App\Models\ParentModel;
use DB;
/**
 * 微信菜单model
 * @author puppet
 */
class WechatMenu extends ParentModel{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'wechat_menu';

    /**
     * DB::直接操作的数据库表名
     * @var string
     */
    protected $db_table = 'pt_wechat_menu';

    /**
     * 主键 默认id。
     *
     * @var string
     */
    protected $primaryKey = 'menu_id';
    
    /**
     * 指定是否模型应该被戳记时间。
     *
     * @var bool
     */
    public $timestamps = false;
    
    public function getCount(){
        return DB::table($this->table)->count();
    }
    
    public function getSubCount($fid = 0){
        return DB::table($this->table)->where('fid',$fid)->where('status',1)->count();
    }
    
    /**
     * 所有信息
     */
    public function getAllData(){
        $result = DB::table($this->table)->where('fid',0)->where('status',1)->orderBy('sort','asc')->get();
        if(!empty($result)){
            foreach ($result as $key => $val){
                $val['sub'] = DB::table($this->table)->where('fid',$val['menu_id'])->where('status',1)->orderBy('sort','asc')->get();
                $result[$key] = $val;
            }
        }
        return $result;
    }
    
    public function getAjaxAllData(){
        $result = [];
        $fdata = DB::table($this->table)->where('fid',0)->where('status',1)->orderBy('sort','asc')->get();
        if(!empty($fdata)){
            foreach ($fdata as $fval){
                $result[] = $fval;
                $sub = DB::table($this->table)->where('fid',$fval['menu_id'])->where('status',1)->orderBy('sort','asc')->get();
                if(!empty($sub)){
                    foreach ($sub as $sval){
                        $result[] = $sval;
                    }
                }
            }
        }
        $res['data'] = $result;
        $res['total'] = count($result);
        return $res;
    }
}