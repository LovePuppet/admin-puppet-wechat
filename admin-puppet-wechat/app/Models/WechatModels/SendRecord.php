<?php
namespace App\Models\WechatModels;
use App\Models\ParentModel;
use DB;
/**
 * 发送信息记录model
 * @author puppet
 */
class SendRecord extends ParentModel{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'send_record';

    /**
     * DB::直接操作的数据库表名
     * @var string
     */
    protected $db_table = 'pt_send_record';

    /**
     * 主键 默认id。
     *
     * @var string
     */
    protected $primaryKey = 'send_record_id';
    
    /**
     * 指定是否模型应该被戳记时间。
     *
     * @var bool
     */
    public $timestamps = false;
    
    public function insertSendRecord($result){
//        \XLog::info("in.result=". json_encode($result,JSON_UNESCAPED_UNICODE), __FILE__, __FUNCTION__, __LINE__);
        $data['openid'] = isset($result['touser']) ? $result['touser'] : '';
        $data['msgtype'] = isset($result['msgtype']) ? $result['msgtype'] : '';
        $data['content'] = '';
        switch ($result['msgtype']){
            case 'text':
                $data['content'] = $result['text']['content'];
                break;
            case 'image':
                $data['content'] = $result['image']['media_id'];
                break;
            case 'mpnews':
                $data['content'] = $result['mpnews']['media_id'];
                break;
        }
        $data['kf_account'] = isset($result['customservice']) ? $result['customservice']['kf_account'] : '';
        $data['create_time'] = time();
        $this->db_insert($data);
        return true;
    }
}