<?php
/**
 * 异常处理
 * Date: 2018/11/14
 * Time: 16:07
 */

namespace Leaf;

class Error{
    public static function init()
    {
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
        $exception = new ErrorException($errno, $errstr, $errfile, $errline);
        var_dump($exception);

    }

    /**
     * 异常处理
     * @access public
     * @param  \Exception|\Throwable $e 异常
     * @return void
     */
    public static function appException($e)
    {
        if (!$e instanceof \Exception) {
            $e = new ThrowableError($e);
            var_dump($e);
        }

    }

    /**
     * 异常中止处理
     * @access public
     * @return void
     */
    public static function appShutdown()
    {
        // 将错误信息托管至 think\ErrorException
        if (!is_null($error = error_get_last()) && self::isFatal($error['type'])===1) {
            self::appException(new ErrorException(
                $error['type'], $error['message'], $error['file'], $error['line']
            ));
        }
    }

    /**
     * 确定错误类型
     * @access protected
     * @param  int $type 错误类型
     * @return bool
     */
    protected static function isFatal($type)
    {
        //致命错误
        if (in_array($type, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]))
        {
            return 1;
        }
        //通知类错误
        if (in_array($type, [E_NOTICE, E_USER_NOTICE]))
        {
            return 2;
        }
        //警告类错误
        if (in_array($type, [E_WARNING, E_CORE_WARNING, E_COMPILE_WARNING, E_USER_WARNING]))
        {
            return 3;
        }

        return false;
    }

}


?>