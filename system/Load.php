<?php
/**
 * 文件加载器
 * Date: 2018/11/14
 * Time: 11:32
 */

namespace Leaf;

class Load{

    /**
     * 初始化
     */
    public static function initialize()
    {
        spl_autoload_register( 'Leaf\\Load::autoload', true, true);
    }


    /**
     * 加载文件
     * @param $className 类名
     */
    public static function autoload($className)
    {
        $classPath = pathinfo($className);
        $path_array = explode(DIRECTORY_SEPARATOR,$classPath['dirname']);

        //系统文件路径
        if($path_array[0] === 'Leaf'){
            $dir = SYS_PATH.str_replace("Leaf".DIRECTORY_SEPARATOR,'',$className).".php";
            if(file_exists($dir)){
                //echo $dir;
                include $dir;
            }
        }

    }

}


//加载系统方法
require SYS_PATH.'Common.php';