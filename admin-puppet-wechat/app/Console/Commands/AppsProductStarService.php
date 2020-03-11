<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Log;
/**
 * Apps应用星级 每天一次
 */
class AppsProductStarService extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AppsProductStarService';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每天23点计算Apps应用星级平均值';
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        Log::info(date('Y-m-d H:i:s').'执行了一次');
        $this->productStar();
    }
    
    /**
     * 订单综合评分
     */
    public function productStar() {
        $result = DB::select("SELECT product_id FROM as_product WHERE `status` = ?",[1]);
        if(!empty($result)){
            foreach ($result as $row){
                $avg_star = DB::select("SELECT AVG(star) AS star FROM as_user_star WHERE product_id = ?",[$row['product_id']]);
                if(isset($avg_star[0]['star']) && !empty($avg_star[0]['star'])){
                    $star = round($avg_star[0]['star'],1);
                    DB::update("UPDATE as_product SET star = ? WHERE product_id = ?",[$star,$row['product_id']]);
                }
            }
        }
    }
}
