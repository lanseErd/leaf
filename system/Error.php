<?php
/**
 * 异常处理
 * Date: 2018/11/14
 * Time: 16:07
 */

namespace Leaf;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Error{
    public static function init(){
        error_reporting(E_ALL);
        set_error_handler([__CLASS__, 'appError']);
        set_exception_handler([__CLASS__, 'appException']);
        register_shutdown_function([__CLASS__, 'appShutdown']);
    }

    /**
     * 错误处理
     * @access public
     * @param  integer $errno      错误编号
     * @param  integer $errstr     详细错误信息
     * @param  string  $errfile    出错的文件
     * @param  integer $errline    出错行号
     * @return void
     * @throws ErrorException
     */
    public static function appError($errno, $errstr, $errfile = '', $errline = 0)
    {
        echo 2;
        $log = new Logger('name');
        $log->pushHandler(new StreamHandler('logs/your.log', Logger::WARNING));

        // add records to the log
        $log->warning('Foo'.$errstr);
        $log->error('Bar');

    }

    /**
     * 异常处理
     * @access public
     * @param  \Exception|\Throwable $e 异常
     * @return void
     */
    public static function appException($e)
    {
        var_dump($e);

    }

    /**
     * 异常中止处理
     * @access public
     * @return void
     */
    public static function appShutdown()
    {
        //
    }

}


?>