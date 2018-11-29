<?php
/**
 * 公共方法
 * Date: 2018/11/15
 * Time: 16:44
 */


//文件加载
if ( ! function_exists('load_file'))
{
    /*
     * @param  string $file_name 文件名
     * @param  string $path items项目目录，system系统目录
     * @return FilePointer
     */
    function load_file($file_name,$path='items')
    {
        $file_path = pathinfo($file_name);
        $dir = APP_PATH.$file_path['dirname'].DIRECTORY_SEPARATOR.$file_path['basename'].'.php';
        if($path==='system')
        {
            $dir = SYS_PATH.$file_path['dirname'].DIRECTORY_SEPARATOR.$file_path['basename'].'.php';
        }
        if(file_exists($dir))
        {
            include $dir;
        }

    }
}


/**
 * 返回经addslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */

if ( ! function_exists('new_addslashes')) {

    function new_addslashes($string)
    {
        if (!is_array($string)) return addslashes($string);
        foreach ($string as $key => $val) {
            if (is_array($val)) {
                $string[$key] = new_addslashes(($val));
            } else {
                $string[$key] = new_addslashes(trim($val));
            }

        }
        return $string;
    }
}