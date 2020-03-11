<?php
namespace App\Models\WechatModels;
use App\Models\ParentModel;
use DB;
/**
 * 微信群发记录model
 * @author puppet
 */
class GroupSend extends ParentModel{
  /**
   * 与模型关联的数据表。
   *
   * @var string
   */
  protected $table = 'group_send_record';

  /**
   * DB::直接操作的数据库表名
   * @var string
   */
  protected $db_table = 'pt_group_send_record';

  /**
   * 主键 默认id。
   *
   * @var string
   */
  protected $primaryKey = 'group_send_record_id';

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
    $cou_sql = "SELECT count(*) as count FROM {$this->db_table} WHERE 1 = ?";
    $sql = "SELECT * FROM {$this->db_table} WHERE 1 = ?";
    $values = [1];
    $count = DB::select($cou_sql,$values);
    $count = isset($count[0]['count']) ? $count[0]['count'] : 0;
    if(isset($params['order']) && !empty($params['order'])){
      $order_db = ['group_send_record_id'];
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
    if(!empty($res['data'])){
      foreach ($res['data'] as $key => $val){
//        $val['lang_name'] = \App\Components\Tools::langName($val['lang']);
//        $val['customer_type_name'] = \App\Components\Tools::customerTypeName($val['customer_type']);
        $val['material_type_name'] = \App\Components\Tools::materialTypeName($val['material_type']);
        $val['tag_names'] = $this->getTagsName($val['tags']);
        $val['content'] = '';
        switch ($val['material_type']){
          case 1:
            $oText = new \App\Models\MaterialModels\Text();
            $text_data = $oText->db_get(['text_id'=>['=',$val['material_id']],'status'=>['=',1]]);
            $val['content'] = !empty($text_data) ? $text_data['content'] : '';
            break;
          case 2:
            $oImage = new \App\Models\MaterialModels\Image();
            $image_data = $oImage->db_get(['image_id'=>['=',$val['material_id']],'status'=>['=',1]]);
            $val['content'] = !empty($image_data) ? $image_data['url'] : '';
            break;
          case 3:
            $oNews = new \App\Models\MaterialModels\News();
            $news_data = $oNews->db_get(['news_id'=>['=',$val['material_id']],'status'=>['=',1]]);
            $val['content'] = '';
            if(!empty($news_data)){
              $images = json_decode($news_data['url'],true);
              $val['content'] = $images[0];
            }
            break;
          case 6:
            $oVideo = new \App\Models\MaterialModels\Video();
            $video_data = $oVideo->db_get(['video_id'=>['=',$val['material_id']],'status'=>['=',1]]);
            $val['content'] = !empty($video_data) ? $video_data['title'] : '';
            break;
        }
        $res['data'][$key] = $val;
      }
    }
    $res['total'] = $count;
    //总条数记录
    return $res;
  }
  
  public function getTagsName($tags){
    $result = '';
    if(!empty($tags)){
      $tag_arr = explode(',',$tags);
      $oTag = new \App\Models\UserModels\Tag();
      foreach ($tag_arr as $tag){
        $tag_data = $oTag->db_get(['tag_id'=>['=',$tag],'status'=>['=',1]]);
        !empty($tag_data) && $result .= $tag_data['name'] .',';
      }
      $result = substr($result,0,-1);
    }
    return $result;
  }
}