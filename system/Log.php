<?php
/**
 * 日志处理
 * Date: 2018/11/14
 * Time: 23:12
 */

namespace Leaf;
use Leaf\Config as Config;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Processor\WebProcessor;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\MemoryPeakUsageProcessor;



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
    private $log_obj;

    public function __construct()
    {
        self::$log_con = Config::get('logger');
        $this->log_obj = new Logger('Leaf');
    }



    /**
     * 写入日志
     * @param int $err_no 错误等级
     * @param String $log_file 文件名
     * @param String $message 错误信息
     * @throws \Exception
     */
    private function instantiation(int $err_no, String $log_file, String $message)
    {
        $stream_handler = new RotatingFileHandler('logs/'.$log_file,self::$log_con['log_length']); // 过滤级别
        $stream_handler->setFormatter(new LineFormatter());
        $this->log_obj->pushHandler($stream_handler);


        //当前url信息
        $web_url = new WebProcessor();
        $this->log_obj->pushProcessor($web_url);

        $this->log_obj->{$this->write_method($err_no)}($message);
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
        $this->instantiation($err_no,self::$log_con['error_name'],$message);
    }

    /**
     * 运行日志
     *
     */
    public function run_log()
    {
        $stream_handler = new RotatingFileHandler('logs/'.self::$log_con['run_log'],self::$log_con['log_length']); // 过滤级别
        $stream_handler->setFormatter(new LineFormatter());
        $this->log_obj->pushHandler($stream_handler);


        //当前url信息
        $web_url = new WebProcessor();
        $this->log_obj->pushProcessor($web_url);

        //增加当前脚本的文件名和类名等信息。
        $intspen = new IntrospectionProcessor();
        $this->log_obj->pushProcessor($intspen);

        //增加当前内存使用情况信息。
        $Memory = new MemoryUsageProcessor();
        $this->log_obj->pushProcessor($Memory);

        //增加内存使用高峰时的信息。
//        $MemoryPeak = new MemoryPeakUsageProcessor();
//        $log->pushProcessor($MemoryPeak);

        $this->log_obj->INFO('Run_log');
    }

    /**
     * 用户自定义日志
     *
     */
    public static function user_defined_log()
    {
        //
    }

}

?>