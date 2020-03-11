<?php
/**
 * User:    yihooying
 * Email:   954650386@qq.com
 * Date:    2017/7/11
 * Time:    下午3:54
 */

class XLog
{
    /*
     * log level
     * 1: error
     * 3: info
     * 5: debug
     */

    private static $_strLogFile = '/data/log/xlog.txt';     // log file
    private static $_intLevel   = 5;                        // log level
    private static $_arrLevelInfo = array(
        1   => 'error',
        3   => 'info',
        5   => 'debug'
    );

    public static function init($strLogFile = "/xlog.txt", $intLevel = 5)
    {
        self::$_strLogFile  = "/data/log".$strLogFile;
        self::$_intLevel    = $intLevel;
    }

    public static function error($str = "", $file = "", $func = "", $line = "", $ret = 0, $user = "")
    {
        self::doLog($str, $file, $func, $line, $ret, $user, 1);
    }

    public static function info($str = "", $file = "", $func = "", $line = "", $ret = 0, $user = "")
    {
        self::doLog($str, $file, $func, $line, $ret, $user, 3);
    }

    public static function debug($str = "", $file = "", $func = "", $line = "", $ret = 0, $user = "")
    {
        self::doLog($str, $file, $func, $line, $ret, $user, 5);
    }

    public static function doLog($str, $file, $func, $line, $ret, $user, $level)
    {
        // if user log level > system level, return;
        if($level > self::$_intLevel)
        {
            return;
        }

        $datetime   = date("Y-m-d H:i:h", time());

        // if not set, set default
        if(!isset($_COOKIE['transid']))
        {
            $_COOKIE['transid'] = md5(time());
        }
        $transid    = isset($_COOKIE['transid']) ? $_COOKIE['transid'] : '';
        $str = "[$datetime][".self::$_arrLevelInfo[$level]."][transid:$transid][user:$user][file:$file][func:$func][line:$line][ret:$ret]:$str \n";
        error_log($str, 3, self::$_strLogFile);
    }
}