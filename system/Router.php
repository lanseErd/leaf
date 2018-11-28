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

    private static $method;

    private static $param;

    private static $ext;

    private static $param_assets;

    private $request;


    public static function external()
    {
        return [self::$http,self::$method,self::$param];
    }

    /**
     * 注册路由
     * @param array $alias
     * @param array $rule
     * @throws \Exception
     */
    public static function init(array $alias, array $rule = [])
    {
        self::$param_assets = pathinfo(Request::$param_url);
        $http_url = str_replace(['\\','/'],['',DIRECTORY_SEPARATOR],self::$param_assets['dirname']);
        if(!empty($http_url))
        {
            $http_url .= DIRECTORY_SEPARATOR;
        }
        $http_url .= self::$param_assets['basename'];

        if(!empty($alias) && !empty($http_url))
        {
            //判断是否有默认方法，有的话就在常规方法里处理删掉这项
            if(!empty($alias['leaf_default']))
            {
                unset($alias['leaf_default']);
            }

            foreach($alias as $key=>$val)
            {
                $route_path = substr($key,0,strpos($key,'?')) ?: $key;

                $http_url_verify = array_filter(explode(DIRECTORY_SEPARATOR,$http_url));
                $http_url_verify = implode('/',$http_url_verify).'/';

                //加上斜杠方便匹配
                $is_req_dir = pathinfo($http_url_verify);
                if(strlen($is_req_dir['dirname']) <=1 )
                {
                    $http_url_verify = $is_req_dir['filename'].'/';
                }

                if($route_path[strlen($route_path)-1]!=='/')
                {
                    $route_path = $route_path.'/';
                }
                //判断有没有申明该路由
                if(substr($http_url_verify,0,strlen($route_path)) === $route_path){

                    //判断请求方式
                    if(!empty($val['method']))
                    {
                        self::{$val['method']}();
                    }
                    else if(!empty($rule['method']))
                    {
                        self::{$rule['method']}();
                    }
                    //验证扩展名
                    if(!empty($val['ext']))
                    {
                        self::extension($val['ext']);
                    }
                    else if(!empty($rule['ext']))
                    {
                        self::extension($rule['ext']);
                    } else {
                        self::extension();
                    }

                    //self::$http = substr($val[0],0,strripos($val[0],'/'));
                    //self::$method = substr($val[0],strripos($val[0],'/')+1,strlen($val[0]));

                    self::$http = self::is_controller_method($val[0]);

                    $rule_fun = ($val['param']??$rule['param'])??null;
                    $rule_key = substr($key,strlen($route_path),strlen($key))?:null;
                    $rule_val = substr($http_url_verify,strlen($route_path),strlen($http_url_verify))?:null;
                    //对参数进行解析
                    if(!is_null($rule_key) && !is_null($rule_val)){
                        self::param_parse($rule_key,$rule_val,$rule_fun);
                    }
                }else{

                    //验证扩展名
                    self::extension();
                    //判断默认路由规则
                    //echo $http_url_verify;
                    $route_path = self::is_controller_method($http_url_verify);
                    $_method = '';
                    if(!empty(self::$method)){
                        $_method = '/'.self::$method;
                    }
                    $rule_val = substr($http_url_verify,strlen($route_path.$_method),strlen($http_url_verify))?:null;
                    //对参数进行解析
                    if(!is_null($rule_val)){
                        self::param_parse(null,$rule_val,null);
                    }
                    if(!empty($route_path)){
                        $route_path = str_ireplace(DIRECTORY_SEPARATOR,'/',$route_path);
                        self::$http = $route_path;
                    }else{
                        //没有该接口
                        throw new \Exception(Lang::get('no_address')."：".$http_url);
                    }
                }
            }

        }
        //默认的路由规则加载
        else {
            if(!empty($alias['leaf_default'])){
                if(!empty($rule['method']))
                {
                    self::{$rule['method']}();
                }
                $route_path = self::is_controller_method($alias['leaf_default']);
                if(!empty($route_path)){
                    $route_path = str_ireplace(DIRECTORY_SEPARATOR,'/',$route_path);
                    self::$http = $route_path;
                }else{
                    //没有该接口
                    throw new \Exception(Lang::get('no_address')."：".$alias['leaf_default']);
                }
            }else{
                //默认方法不能为空
                throw new \Exception(Lang::get('empty_leaf_default'));
            }
        }


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
                    $param_arr[str_replace('?','',$param)] = $val;
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
            if($param_arr){
                self::$param = $param_arr;
            }else{
                throw new \Exception(Lang::get('error_param_verify').self::$http.' ');
            }

        }else{
            if(!empty($val_incise)){
                self::$param = str_replace('.'.Config::get('default_ext'),'',$val_incise);
            }else{
                throw new \Exception(Lang::get('error_param_verify').self::$http.' ');
            }
        }

    }

    /**
     * 请求方式验证get
     * @throws \Exception
     */
    private static function get()
    {
        if(strtolower(Request::$method) !== __FUNCTION__)
        {
            throw new \Exception(Lang::get('no_request_method').__FUNCTION__);
        }
    }

    /**
     * 请求方式验证post
     * @throws \Exception
     */
    private static function post()
    {
        if(strtolower(Request::$method) !== __FUNCTION__)
        {
            throw new \Exception(Lang::get('no_request_method').__FUNCTION__);
        }
    }

    /**
     * 请求方式验证put
     * @throws \Exception
     */
    private static function put()
    {
        if(strtolower(Request::$method) !== __FUNCTION__)
        {
            throw new \Exception(Lang::get('no_request_method').__FUNCTION__);
        }
    }

    /**
     * 请求方式验证delete
     * @throws \Exception
     */
    private static function delete()
    {
        if(strtolower(Request::$method) !== __FUNCTION__)
        {
            throw new \Exception(Lang::get('no_request_method').__FUNCTION__);
        }
    }

    /**
     * 扩展名验证
     * @throws \Exception
     */
    private static function extension(string $ext=null)
    {
        $extension = self::$param_assets['extension'] ?? null;
        if($extension !== $ext && $extension!== Config::get('default_ext'))
        {
            throw new \Exception(Lang::get('error_extension').$ext);
        }else{
            self::$ext = $ext;
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
            while($i<$array_length){
                if(file_exists('controller'.DIRECTORY_SEPARATOR.$array_path[$i].'.php')){
                    if(!empty($array_path[$i+1])){
                        self::$method = $array_path[$i+1];
                    }
                    return $array_path[$i];
                }else if(file_exists('controller'.DIRECTORY_SEPARATOR.$array_path[$i])) {
                    if (!empty($array_path[$i + 1])) {
                        $array_path[$i + 1] = $array_path[$i] . DIRECTORY_SEPARATOR . $array_path[$i + 1];
                    }
                }
                $i++;
            }
        }

        return false;
    }

}
