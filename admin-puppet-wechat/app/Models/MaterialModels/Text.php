<?php
namespace App\Models\MaterialModels;
use App\Models\ParentModel;
use DB;
/**
 * 文本素材model
 * @author puppet
 */
class Text extends ParentModel{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'material_text';

    /**
     * DB::直接操作的数据库表名
     * @var string
     */
    protected $db_table = 'pt_material_text';

    /**
     * 主键 默认id。
     *
     * @var string
     */
    protected $primaryKey = 'text_id';
    
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
        $cou_sql = "SELECT count(*) as count FROM {$this->db_table} WHERE `status` = ?";
        $sql = "SELECT text_id,crc_token,content,create_time FROM {$this->db_table} WHERE `status` = ?";
        $values = [1];
        if(isset($params['search']['value']) && !empty($params['search']['value'])){
          $cou_sql .= " AND content like '%".$params['search']['value']."%'";
          $sql .= " AND content like '%".$params['search']['value']."%'";
        }
        $count = DB::select($cou_sql,$values);
        $count = isset($count[0]['count']) ? $count[0]['count'] : 0;
//        if(isset($params['order']) && !empty($params['order'])){
//            $order_db = ['text_id'];
//            $sql .= " ORDER BY ";
//            foreach ($params['order'] as $order){
//                $result = isset($order_db[$order['column']]) ? $order_db[$order['column']] : '';
//                !empty($result) && $sql .= $result.' '.$order['dir'].',';
//            }
//            $sql = substr($sql,0,-1);
//        }
        $sql .= " ORDER BY create_time desc";
        $sql .= " LIMIT $limit_sta,$limit_num";
        $order_list = DB::select($sql, $values);
        $res['data'] = $order_list;
//        if(!empty($res['data'])){
//            foreach ($res['data'] as $key => $val){
//                $val['content'] = json_decode($val['content']);
//                $res['data'][$key] = $val;
//            }
//        }
        $res['total'] = $count;
        //总条数记录
        return $res;
    }
    
    /**
     * 根据crc_token
     * 找到对应的内容
     */
    public function getContent($key = ''){
        \XLog::info("in.key=".$key, __FILE__, __FUNCTION__, __LINE__);
        $content = '消息已被关闭或删除';
        if(!empty($key)){
            $data = $this->db_get(['crc_token'=>['=',$key],'status'=>['=',1]]);
            !empty($data) && $content = $data['content'];
        }
        \XLog::info("out.content=".$content, __FILE__, __FUNCTION__, __LINE__);
        return $content;
    }
}