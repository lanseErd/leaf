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
        $this->server = $_SERVER;
        self::$start_time = $this->server['REQUEST_TIME_FLOAT'];
        $this->request_method();
        $this->request_url();
    }


    /**
     * 请求方式
     */
    private function request_method()
    {
        self::$method = $this->server['REQUEST_METHOD'];
    }


    /**
     * url处理
     */
    private function request_url(){
        $url = pathinfo(strtolower($this->server['REQUEST_URI']));
        foreach($url as $key=>$val)
        {
            if($key === 'dirname')
            {
                $path = explode('/',$val);
                $path = array_filter($path);
                foreach($path as $path_val){
                    $path_url[] = $path_val;
                    //preg_match('/(^\w+$)/', $val, $matches);
                }
                self::$param_url['dirname'] = $path_url;
            }else{
                self::$param_url[$key] = $val;
            }
        }
    }
}
?>