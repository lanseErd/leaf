<?php
/**
 * 加载系统配置文件
 * Date: 2018/11/15
 * Time: 21:09
 */

namespace Leaf;

class Config{

    /**
     * @var array 配置列表
     */
    private static $configs = [];


    /**
     * 设置配置
     * @access public
     * @param $file_config 文件名
     */
    public static function set($file_config)
    {
        if(!empty($file_config))
        {
            foreach($file_config as $key=>$val)
            {
                if(empty(self::$configs[$key]))
                {
                    self::$configs[$key]=$val;
                }

            }
        }
    }


    /**
     * 获取配置
     * @access public
     * @param $config_name 配置名
     */
    public static function get($config_name)
    {
        if(isset(self::$configs[$config_name]))
        {
            return self::$configs[$config_name];
        }

        return false;
    }

}