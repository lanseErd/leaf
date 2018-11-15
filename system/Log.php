<?php
/**
 * 日志处理
 * Date: 2018/11/14
 * Time: 23:12
 */

namespace Leaf;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Processor\WebProcessor;

class Log{

    public function __construct()
    {

        $log = new Logger('name');
        $stream_handler = new StreamHandler('logs/your.log'); // 过滤级别
        $stream_handler->setFormatter(new LineFormatter());
        $log->pushHandler($stream_handler);

        $web_url = new WebProcessor();
        $log->pushProcessor($web_url);


        $log->error('Bar',[$errfile.' Line:'.$errline.' '.$errstr]);
    }

}

?>