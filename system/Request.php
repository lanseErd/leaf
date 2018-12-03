<?php
/**
 * 请求处理
 * Date: 2018/11/13
 * Time: 23:10
 */

namespace Leaf;

class Request{


    private $server;

    public static $method;

    public static $param_url=[];

    private static $start_time;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        include SYS_PATH . "functions/SafetyFilter.php";
        $this->server = $_SERVER;
        self::$start_time = $this->server['REQUEST_TIME_FLOAT'];
        self::$param_url = $this->server['REQUEST_URI'];
        $this->request_method();
    }


    /**
     * 请求方式
     */
    private function request_method()
    {
        self::$method = $this->server['REQUEST_METHOD'];
    }

}
?>