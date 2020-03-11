<?php
namespace App\Models\WechatModels;
use App\Models\ParentModel;
use DB;
/**
 * 微信记录model
 * @author puppet
 */
class Record extends ParentModel{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'record';

    /**
     * DB::直接操作的数据库表名
     * @var string
     */
    protected $db_table = 'pt_record';

    /**
     * 主键 默认id。
     *
     * @var string
     */
    protected $primaryKey = 'record_id';
    
    /**
     * 指定是否模型应该被戳记时间。
     *
     * @var bool
     */
    public $timestamps = false;
    
    public function insertRecord($oPost){
      $result = $this->db_get(['openid'=>['=',$oPost->FromUserName]]);
      if(empty($result)){
        $data['openid'] = trim($oPost->FromUserName);
        $data['msgtype'] = isset($oPost->MsgType) ? trim($oPost->MsgType) : '';
        $data['event'] = isset($oPost->Event) ? trim($oPost->Event) : '';
        $data['event_key'] = isset($oPost->EventKey) ? trim($oPost->EventKey) : '';
        $data['content'] = isset($oPost->Content) ? trim($oPost->Content) : '';
        $data['create_time'] = time();
        $this->db_insert($data);
      }else{
        $data['msgtype'] = isset($oPost->MsgType) ? trim($oPost->MsgType) : '';
        $data['event'] = isset($oPost->Event) ? trim($oPost->Event) : '';
        $data['event_key'] = isset($oPost->EventKey) ? trim($oPost->EventKey) : '';
        $data['content'] = isset($oPost->Content) ? trim($oPost->Content) : '';
        $data['1m_reply'] = 0;
        $data['create_time'] = time();
        $this->db_update('',$result['record_id'],$data);
      }
      return true;
    }
    
    public function deleteRecord($openid){
      $sql = "delete from {$this->db_table} where openid= ?";
      return DB::delete($sql,[$openid]);
    }
}