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