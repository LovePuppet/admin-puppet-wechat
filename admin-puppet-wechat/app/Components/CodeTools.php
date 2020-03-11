<?php
namespace App\Components;
/**
 * 接口返回code状态编号
 * @author 郭钊林
 */
class CodeTools {
    public $result = [
        'code'=>[
            '-1' => '缺少参数',
            '100' => '请求成功',
            //STM信息10开头
            '100001' => '型号不存在',
            '100002' => '版本数据不存在',
        ]
    ];
}