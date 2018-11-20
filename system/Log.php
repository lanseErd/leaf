<?php
/**
 * 日志处理
 * Date: 2018/11/14
 * Time: 23:12
 */

namespace Leaf;
use Leaf\Config;
use Leaf\Debug;
use Monolog\Logger;
use Monolog\Handler\{StreamHandler,RotatingFileHandler};
use Monolog\Formatter\{LineFormatter,HtmlFormatter};
use Monolog\Processor\{WebProcessor,IntrospectionProcessor,MemoryUsageProcessor,MemoryPeakUsageProcessor};



class Log{


    /**
     * @var bool|mixed 加载配置文件
     */
    private static $log_con;


    /**
     * @var 写入方法
     */
    private $write_method;


    /**
     * @var Logger 日志实例
     */
    private static $log_obj;

    public function __construct()
    {
        self::$log_con = Config::get('logger');
        self::$log_obj = new Logger('Leaf');
    }

    /**
     * 根据错误等级选择写入方法
     * @param int $err_no 错误等级
     */
    private function write_method(int $err_no)
    {
        switch ($err_no)
        {
            case 1:
                $this->write_method = "ERROR";
                break;
            case 2:
                $this->write_method = "NOTICE";
                break;
            case 3:
                $this->write_method = "WARNING";
                break;
            default:
                $this->write_method = "INFO";
        }
        return $this->write_method;
    }


    /**
     * 错误日志
     * @param int $errstr 错误编号
     * @param String $message 错误信息
     */
    public function anomaly_log(int $err_no, String $message)
    {
        $stream_handler = new RotatingFileHandler('logs/'.self::$log_con['error_name'],self::$log_con['log_length']); // 过滤级别
        $stream_handler->setFormatter(new LineFormatter());
        self::$log_obj->pushHandler($stream_handler);

        //当前url信息
        $web_url = new WebProcessor();
        self::$log_obj->pushProcessor($web_url);

        self::$log_obj->{$this->write_method($err_no)}($message);
    }

    /**
     * 运行日志
     *
     */
    public function run_log()
    {
        $stream_handler = new RotatingFileHandler('logs/'.self::$log_con['run_log'],self::$log_con['log_length']); // 过滤级别
        $stream_handler->setFormatter(new LineFormatter());
        self::$log_obj->pushHandler($stream_handler);


        //当前url信息
        $web_url = new WebProcessor();
        self::$log_obj->pushProcessor($web_url);

        //增加当前脚本的文件名和类名等信息。
        $intspen = new IntrospectionProcessor();
        self::$log_obj->pushProcessor($intspen);

        //增加当前内存使用情况信息。
        $Memory = new MemoryUsageProcessor();
        self::$log_obj->pushProcessor($Memory);

        //增加内存使用高峰时的信息。
//        $MemoryPeak = new MemoryPeakUsageProcessor();
//        $log->pushProcessor($MemoryPeak);

        self::$log_obj->INFO('Run_log');
    }


    /**
     * 用户自定义日志
     * @param String $file_name 文件名
     * @param String $message 消息
     * @param array $mes_array 自定义数组
     * @return bool
     * @throws \Exception
     */
    public function user_defined_log(String $file_name, String $message, array $mes_array = [])
    {
        $file_extension = pathinfo($file_name);
        if($file_extension['extension']!='log') return false;
        $stream_handler = new StreamHandler('logs/'.$file_name); // 过滤级别
        $stream_handler->setFormatter(new LineFormatter());
        self::$log_obj->pushHandler($stream_handler);
        self::$log_obj->INFO('user_defined: '.$message,$mes_array);
    }

    /**
     * 调试信息
     * @param int $errstr 错误编号
     * @param String $message 错误信息
     * @throws \Exception
     */
    public function debug(int $err_no, String $message)
    {
        $log = new Logger('Debug');
        $stream_handler = new Debug();
        $stream_handler->setFormatter(new HtmlFormatter());
        $log->pushHandler($stream_handler);

        //当前url信息
        $web_url = new WebProcessor();
        $log->pushProcessor($web_url);

        $log->{$this->write_method($err_no)}($message);
    }

}

?>