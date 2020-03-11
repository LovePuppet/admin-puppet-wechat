<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

/**
 * 连接主库Model
 */
class ParentModel extends Model{
    protected $table;
    
    protected $db_table;
    
    protected $primaryKey;
    /**
     * 数据插入
     * @return true or false
     */
    public function db_insert($data){
        return DB::table($this->table)->insert($data);
    }
    
    /**
     * 数据插入
     * @return $id
     */
    public function db_insertGetId($data){
        return DB::table($this->table)->insertGetId($data);
    }
    
    /**
     * 修改
     * $cond_key 条件key
     * $cond_value 条件值
     * $data    修改数据
     */
    public function db_update($cond_key = '',$cond_value,$data){
        if(empty($cond_key))
            return DB::table($this->table)->where($this->primaryKey,$cond_value)->update($data);
        else
            return DB::table($this->table)->where($cond_key,$cond_value)->update($data);
    }
    
    /**
     * 获取单条数据
     * $data = ['admin_id'=>['=','']]
     */
    public function db_get($data){
        $sql = "SELECT * FROM {$this->db_table} WHERE";
        $tmp = 0;
        $values = [];
        foreach ($data as $key => $val){
            $values[] = $val[1];
            if($tmp == 0)
                $sql .= " $key $val[0] ?";
            else
                $sql .= " AND $key $val[0] ?";
            $tmp++;
        }
        $sql .= " limit 1";
        $data = DB::select($sql,$values);
        return !empty($data) ? $data[0] : false;
    }
    
    /*
     * 删除
     * $cond_key 条件key
     * $cond_value 条件值
     */
    public function db_delete($cond_key = '',$cond_value){
        if(empty($cond_key))
            return DB::table($this->table)->where($this->primaryKey,$cond_value)->delete();
        else
            return DB::table($this->table)->where($cond_key,$cond_value)->delete();
    }
}
