<?php
/**
 * 配置文件
 * Date: 2018/11/15
 * Time: 15:49
 */

//日志
$cn['logger'] = [

    /*
     * 日志大小，以及分割机制
     * @param  string 按天分割文件，保留文件个数，前面的会自动删除，0表示不现在
     */
    'log_length'=>"0",
    //错误日志文件名
    'error_name'=>'error.log',
    //运行日志0关闭 写入文件名表示开启 格式.log
    'run_log'=>0,
];

//调试模式
$cn['debug'] = true;
return $cn;