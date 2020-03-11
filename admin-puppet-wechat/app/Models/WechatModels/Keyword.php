<?php
namespace App\Models\WechatModels;
use App\Models\ParentModel;
use DB;
/**
 * 微信关注/关键词model
 * @author puppet
 */
class Keyword extends ParentModel{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'wechat_keyword';

    /**
     * DB::直接操作的数据库表名
     * @var string
     */
    protected $db_table = 'pt_wechat_keyword';

    /**
     * 主键 默认id。
     *
     * @var string
     */
    protected $primaryKey = 'keyword_id';
    
    /**
     * 指定是否模型应该被戳记时间。
     *
     * @var bool
     */
    public $timestamps = false;
    
    public function getCount(){
        return DB::table($this->table)->count();
    }
    
    /**
     * 翻页展示所有信息
     */
    public function getPageData($params){
        $res = false;
        $limit_num = (isset($params['limit']) && intval($params['limit'])>0 && intval($params['limit']) <= 100) ? $params['limit'] : 10;
        $limit_sta = 0;
        $page = 1;
        if (isset($params['page']) && intval($params['page']) > 0) {
            $limit_sta = intval($limit_num * intval($params['page'] - 1));
            $page = intval($params['page']);
        }
        $cou_sql = "SELECT count(*) as count FROM {$this->db_table} WHERE `status` = ? AND type = ?";
        $sql = "SELECT keyword_id,keyword,content,img_media_id,news_media_id,create_time FROM {$this->db_table} WHERE `status` = ? AND type = ?";
        $values = [1,2];
        $count = DB::select($cou_sql,$values);
        $count = isset($count[0]['count']) ? $count[0]['count'] : 0;
        if(isset($params['order']) && !empty($params['order'])){
            $order_db = ['keyword_id'];
            $sql .= " ORDER BY ";
            foreach ($params['order'] as $order){
                $result = isset($order_db[$order['column']]) ? $order_db[$order['column']] : '';
                !empty($result) && $sql .= $result.' '.$order['dir'].',';
            }
            $sql = substr($sql,0,-1);
        }
        $sql .= " LIMIT $limit_sta,$limit_num";
        $order_list = DB::select($sql,$values);
        $res['data'] = $order_list;
        $res['total'] = $count;
        //总条数记录
        return $res;
    }
}