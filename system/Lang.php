<?php
/**
 * 系统语言包处理程序
 * Date: 2018/11/22
 * Time: 15:55
 */

namespace Leaf;

class Lang{

    protected static $lang = [];

    public static function get(string $key){
        if(empty(self::$lang[$key]))
        {
            $lang = include SYS_PATH . "language".DIRECTORY_SEPARATOR."language.php";
            if(!empty($lang[$key])){
                self::$lang[$key] = $lang[$key];
                return self::$lang[$key];
            }
            return false;
        }
        return self::$lang[$key];
    }
}