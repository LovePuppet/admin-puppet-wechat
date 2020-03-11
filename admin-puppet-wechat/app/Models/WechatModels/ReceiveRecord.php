<?php
namespace App\Models\WechatModels;
use App\Models\ParentModel;
use DB;
/**
 * 接收信息记录model
 * @author puppet
 */
class ReceiveRecord extends ParentModel{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'receive_record';

    /**
     * DB::直接操作的数据库表名
     * @var string
     */
    protected $db_table = 'pt_receive_record';

    /**
     * 主键 默认id。
     *
     * @var string
     */
    protected $primaryKey = 'receive_record_id';
    
    /**
     * 指定是否模型应该被戳记时间。
     *
     * @var bool
     */
    public $timestamps = false;
    
    public function insertReceiveRecord($oPost){
        $data['openid'] = trim($oPost->FromUserName);
        $data['msgtype'] = isset($oPost->MsgType) ? trim($oPost->MsgType) : '';
        $data['event'] = isset($oPost->Event) ? trim($oPost->Event) : '';
        $data['event_key'] = isset($oPost->EventKey) ? trim($oPost->EventKey) : '';
        $data['content'] = isset($oPost->Content) ? trim($oPost->Content) : '';
        $data['create_time'] = time();
        $this->db_insert($data);
        $oRecord = new Record();
        $oRecord->insertRecord($oPost);
        return true;
    }
}