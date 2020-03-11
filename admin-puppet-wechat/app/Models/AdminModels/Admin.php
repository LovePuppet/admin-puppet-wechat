<?php
namespace App\Models\AdminModels;
use App\Models\ParentModel;
use App\Components\Tools;
use DB;
/**
 * 后台管理员model
 * @author puppet
 */
class Admin extends ParentModel{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'admin';

    /**
     * DB::直接操作的数据库表名
     * @var string
     */
    protected $db_table = 'pt_admin';

    /**
     * 主键 默认id。
     *
     * @var string
     */
    protected $primaryKey = 'admin_id';
    
    /**
     * 指定是否模型应该被戳记时间。
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * 验证账号
     */
    public function validAdminLogin($username,$password){
        $res = DB::table($this->table)
                ->where('admin_name',$username)
                ->where('password', Tools::passwordEncryption($password))
                ->where('status',1)
                ->first();
        return !empty($res) ? $res : false;
    }
    
    /**
     * 根据用户名，获取单条用户数据
     */
    public function getAdminData($username){
        $res = DB::table($this->table)
                ->where('admin_name',$username)
                ->where('status','>=',0)
                ->first();
        return !empty($res) ? $res : false;
    }
    
    /**
     * 根据admin_id，获取单条用户数据
     */
    public function getData($id){
        $res = DB::table($this->table)
                ->where($this->primaryKey,$id)
                ->where('status','>=',0)
                ->first();
        return !empty($res) ? $res : false;
    }
    
    /**
     * 所有管理员信息
     */
    public function getAllData(){
        $sql = "SELECT a.admin_id,a.admin_name,a.real_name,a.mobile,a.create_time,a.status,b.role_name FROM {$this->db_table} a LEFT JOIN pt_admin_role b ON (a.role_id = b.admin_role_id) AND a.`status` >=? AND b.`status`>=? WHERE a.`status` >=?;";
        return DB::select($sql,[0,0,0]);
    }
    
    /**
     * 根据role_id角色编号
     * 获取所有权限名称或者权限url
     */
    public function getUserLimitInfo($role_id,$val = 'limit_name'){
        $result = [];
        if($role_id > 0){
            $oAdminRole = new AdminRole;
            $roleinfo = $oAdminRole->db_get(['admin_role_id' =>['=',$role_id],'status'=>['>=',0]]);
            $limits_ids = !empty($roleinfo['limits_ids']) ? (json_decode($roleinfo['limits_ids'],true)) : [];
            !empty($roleinfo) && $result = Tools::getAdminLimitsNameOrUrl($limits_ids,$val);
        }
        return $result;
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
        $cou_sql = "SELECT count(*) as count FROM {$this->db_table} a LEFT JOIN pt_admin_role b ON (a.role_id = b.admin_role_id) AND a.`status` >=? AND b.`status`>=? WHERE a.`status` >=?";
        $sql = "SELECT a.admin_id,a.admin_name,a.real_name,a.mobile,a.create_time,a.status,b.role_name FROM {$this->db_table} a LEFT JOIN pt_admin_role b ON (a.role_id = b.admin_role_id) AND a.`status` >=? AND b.`status`>=? WHERE a.`status` >=?";
        $values = [0,0,0];
        $count = DB::select($cou_sql,$values);
        $count = isset($count[0]['count']) ? $count[0]['count'] : 0;
        if(isset($params['order']) && !empty($params['order'])){
            $order_db = ['a.admin_id','',''];
            $sql .= " ORDER BY ";
            foreach ($params['order'] as $order){
                $result = isset($order_db[$order['column']]) ? $order_db[$order['column']] : '';
                !empty($result) && $sql .= $result.' '.$order['dir'].',';
            }
            $sql = substr($sql,0,-1);
        }
        $sql .= " LIMIT $limit_sta,$limit_num";
        $order_list = DB::select($sql, $values);
        $res['data'] = $order_list;
        $res['total'] = $count;
        //总条数记录
        return $res;
    }
}