<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\RobotModels\Robot;
use App\Models\WechatModels\Record;
use DB;
use Log;
/**
 * Apps应用星级 每天一次
 */
class SendRobotMessageService extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SendRobotMessageService';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每分钟发送微信消息';
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        Log::info(date('Y-m-d H:i:s').'执行了一次');
        $this->wxMsgSendOneMin();
//        $this->wxMsgSendFiveHours();
        $this->wxMsgSendOneTwentyHours();
    }
    
    /**
     * 发送客服消息每分钟
     */
    public function wxMsgSendOneMin() {
        $result = DB::select("SELECT record_id,openid,create_time FROM pt_record WHERE 1m_reply = ?",[0]);
        if(!empty($result)){
            $oRobot = new Robot();
            $oRecord = new Record();
            foreach ($result as $val){
                $nowtime = strtotime('-1Minute');
                if($nowtime >= $val['create_time']){
                    $oRobot->oneMinTimingSend($val['openid']);
                    $oRecord->db_update('',$val['record_id'],['1m_reply'=>1]);
                }
            }
        }
    }
    
    /**
     * 发送客服消息每5小时
     */
    public function wxMsgSendFiveHours() {
        $result = DB::select("SELECT record_id,openid,create_time FROM pt_record WHERE 5h_reply = ?",[0]);
        if(!empty($result)){
            $oRobot = new Robot();
            $oRecord = new Record();
            foreach ($result as $val){
                $nowtime = strtotime('-5hours');
                if($nowtime >= $val['create_time']){    //5小时
                    $oRobot->timingSend($val['openid']);
                    $oRecord->db_update('',$val['record_id'],['5h_reply'=>1]);
                }
            }
        }
    }
    
    /**
     * 发送客服消息每20小时
     */
    public function wxMsgSendOneTwentyHours() {
        $result = DB::select("SELECT record_id,openid,create_time FROM pt_record WHERE 20h_reply = ?",[0]);
        if(!empty($result)){
            $oRobot = new Robot();
            $oRecord = new Record();
            foreach ($result as $val){
                $nowtime = strtotime('-20hours');
                if($nowtime >= $val['create_time']){    //20小时
                    $oRobot->timingSend($val['openid']);
                    $oRecord->db_update('',$val['record_id'],['20h_reply'=>1]);
                }
            }
        }
    }
}
