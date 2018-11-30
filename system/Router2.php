<?php
/**
 * 路由处理
 * Date: 2018/11/19
 * Time: 17:50
 */

namespace Leaf;
use Leaf\Request;

class Router2{

    private static $route_info = [];

    public static function init($alias,$rule,array $manage = [])
    {
        if(is_string($alias))
        {
            $posnum = strpos($alias,'?');
            $route_path = substr($alias,0,$posnum) ?: $alias;

            if(!isset(self::$route_info[$route_path])){
                $rule_path_method = self::is_controller_method($rule);
                $parse_method = parse_url($rule_path_method[1]);
                $array_route['path'] = $rule_path_method[0];
                $array_route['method'] = $parse_method['path'];

                $route_param = '';
                if($posnum){
                    $route_param = str_replace('?','',substr($alias,$posnum,strlen($alias)) ?: '');
                }
                $route_param .= ($parse_method['query'] ?? '')?'?'.$parse_method['query'] ?? '':'';
                $route_param = parse_url($route_param);
                parse_str($route_param['query'],$parr);
                var_dump($parr);
            }

        }
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
        if($array_length){
            $method = '';
            while($i<$array_length){
                if(file_exists('controller'.DIRECTORY_SEPARATOR.$array_path[$i].'.php')){
                    if(!empty($array_path[$i+1])){
                        $method = $array_path[$i+1];
                    }
                    return [$array_path[$i],$method];
                }else if(file_exists('controller'.DIRECTORY_SEPARATOR.$array_path[$i])) {
                    if (!empty($array_path[$i + 1])) {
                        $array_path[$i + 1] = $array_path[$i] . DIRECTORY_SEPARATOR . $array_path[$i + 1];
                    }
                }
                $i++;
            }
        }

        throw new \Exception(Lang::get('no_address').$http_path);
    }


    /**
     * 参数解析
     * @param null $rule_key  路由规则
     * @param $rule_val  参数
     * @param null $rule_fun  处理方法
     * @throws \Exception
     */
    private static function param_parse($rule_key = null, $rule_val, $rule_fun = null)
    {
        //去除空值并重新排序
        $val_incise = function($val_incise){
            $i = 0;
            $val_incises = [];
            if(!empty($val_incise)){
                foreach ($val_incise as $sort)
                {
                    $val_incises[$i] = $sort;
                    $i++;
                }
            }
            return $val_incises;
        };

        $rule_param_val = '';
        //检查有没有额外参数
        $parse = parse_url($rule_val);
        if(!empty($parse['query'])){
            $rule_param_val = $parse['query'];
            $rule_val = $parse['path'];
        }
        $val_incise = $val_incise(array_filter(explode('/',$rule_val)));

        if(!is_null($rule_key))
        {
            $key_incise = explode('/',$rule_key);
            $param_arr = [];
            foreach ($key_incise as $key=>$param)
            {
                if(!empty($val_incise[$key]))
                {
                    $val = str_replace('.'.self::$ext,'',$val_incise[$key]);
                    $param_arr[str_replace('?','',$param)] = new_addslashes($val);
                }
            }
            //闭包验证
            if(!is_null($rule_fun))
            {
                if(is_object($rule_fun))
                {
                    $param_arr = $rule_fun($param_arr);
                }
            }

            //如果有额外参数则解析
            if(!empty($rule_param_val)){
                parse_str($rule_param_val,$parr);
                $param_arr = array_merge($param_arr,$parr);
            }

            if($param_arr){
                self::$param = $param_arr;
            }else{
                throw new \Exception(Lang::get('error_param_verify').self::$http.' ');
            }

        }else{
            if(!empty($val_incise)){
                self::$param = new_addslashes($val_incise);
            }else{
                throw new \Exception(Lang::get('error_param_verify').self::$http.' ');
            }
        }

    }


}

?>