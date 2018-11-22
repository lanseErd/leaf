<?php
/**
 * 路由处理
 * Date: 2018/11/19
 * Time: 17:50
 */

namespace Leaf;
use Leaf\Request;

class Router{

    private static $http;

    private $request;

    public static function init(array $alias,array $rule)
    {
        $param_assets = pathinfo(Request::$param_url);
        $http_url = str_replace(['\\','/'],['',DIRECTORY_SEPARATOR],$param_assets['dirname']);
        if(!empty($http_url))
        {
            $http_url .= DIRECTORY_SEPARATOR;
        }
        $http_url .= $param_assets['basename'];

        if(!empty($alias) && !empty($http_url))
        {
            foreach($alias as $key=>$val)
            {
                $is_param = substr($key,0,strpos($key,'?'));
                if($is_param){
                    $route_path = $is_param;
                }else{
                    $route_path = $key;
                }

                //判断有没有申明该路由
                $http_url_verify = array_filter(explode(DIRECTORY_SEPARATOR,$http_url));
                $http_url_verify = implode('/',$http_url_verify).'/';

                if(substr($http_url_verify,0,strlen($route_path)) === $route_path){
                    self::$http[$val[0]] = 1;
                }else{
                    //判断默认路由规则
                    $route_path = self::is_controller_method($http_url_verify);
                    if(!empty($route_path)){
                        $route_path = str_ireplace(DIRECTORY_SEPARATOR,'/',$route_path);
                        self::$http[$route_path] = 1;
                    }else{
                        //没有该接口
                        throw new \Exception(Lang::get('no_address')."：".$http_url);//使用throw抛出异常
                    }
                }
            }
        }
    }





    public function route()
    {
        //$this->request = new Request();
    }


    /**
     * 验证未定义路由方法是否存在
     * @param $http_path
     */
    public static function is_controller_method($http_path)
    {
        $array_path = explode('/',$http_path);
        $array_length = count($array_path);
        $i=0;
        while($i<$array_length){
            if(file_exists('controller'.DIRECTORY_SEPARATOR.$array_path[$i].'.php')){

                if(!empty($array_path[$i+1])){
                    return $array_path[$i].DIRECTORY_SEPARATOR.$array_path[$i+1];
                }else{
                    return $array_path[$i];
                }
            }else if(file_exists('controller'.DIRECTORY_SEPARATOR.$array_path[$i])) {
                if (!empty($array_path[$i + 1])) {
                    $array_path[$i + 1] = $array_path[$i] . DIRECTORY_SEPARATOR . $array_path[$i + 1];
                }
            }
            $i++;
        }
        return false;
    }

}
