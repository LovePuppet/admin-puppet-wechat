<?php
namespace App\Models\MaterialModels;
use App\Models\ParentModel;
use DB;
/**
 * 图文素材model
 * @author puppet
 */
class News extends ParentModel{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'material_news';

    /**
     * DB::直接操作的数据库表名
     * @var string
     */
    protected $db_table = 'pt_material_news';

    /**
     * 主键 默认id。
     *
     * @var string
     */
    protected $primaryKey = 'news_id';
    
    /**
     * 指定是否模型应该被戳记时间。
     *
     * @var bool
     */
    public $timestamps = false;
    
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
        $sql = "SELECT news_id,media_id,title,url,create_time FROM {$this->db_table} WHERE `status` = ?";
        $values = [1];
        $count = DB::select($cou_sql,$values);
        $count = isset($count[0]['count']) ? $count[0]['count'] : 0;
//        if(isset($params['order']) && !empty($params['order'])){
//            $order_db = ['news_id'];
//            $sql .= " ORDER BY ";
//            foreach ($params['order'] as $order){
//                $result = isset($order_db[$order['column']]) ? $order_db[$order['column']] : '';
//                !empty($result) && $sql .= $result.' '.$order['dir'].',';
//            }
//            $sql = substr($sql,0,-1);
//        }
        $sql .= " ORDER BY create_time desc";
        $sql .= " LIMIT $limit_sta,$limit_num";
        $order_list = DB::select($sql,$values);
        $res['data'] = $order_list;
        if(!empty($res['data'])){
            foreach ($res['data'] as $key => $val){
                $val['url'] = json_decode($val['url'],true);
                $val['fm_url'] = $val['url'][0];
                $val['title'] = json_decode($val['title'],true);
                $title = '';
                foreach ($val['title'] as $t){
                    $title .= $t.'<br>';
                }
                $val['titles'] = htmlspecialchars_decode($title);
                $res['data'][$key] = $val;
            }
        }
        $res['total'] = $count;
        //总条数记录
        return $res;
    }
    
    /**
     * 根据图文消息media_id
     * 返回图文内容
     */
    public function getNewsInfo($media_id){
        $result = [];
        $data = $this->db_get(['media_id'=>['=',$media_id],'status'=>['=',1]]);
        if(!empty(data)){
            $titles = json_decode(data['title'],true);
            $urls = json_decode(data['url'],true);
            $content = json_decode(data['content'],true);
            foreach ($titles as $key => $val){
                $res = [];
                $res['Title'] = $val;
                $res['Description'] = $content[$key]['content'];
                $res['PicUrl'] = $urls[$key];
                $res['Url'] = $content[$key]['url'];
                $result[] = $res;
            }
        }
        return $result;
    }
}