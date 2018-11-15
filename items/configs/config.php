<?php
/**
 * 配置文件
 * Date: 2018/11/15
 * Time: 15:49
 */

//日志
$cn['logger'] = array(

    /*
     * 日志大小，以及分割机制
     * @param  string 0 不分割
     * @param  string 1 按天分割文件
     * @param  string xM 按大小分割，例如10M
     */
    'log_size'=>"0",
    //错误日志文件名
    'error_name'=>'error.log',
    //运行日志文件名
    'run_log'=>'run.log',
);
return $cn;