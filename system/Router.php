<?php
/**
 * 路由处理
 * Date: 2018/11/19
 * Time: 17:50
 */

namespace Leaf;

class Router{


    /**
     * 当前完整的url
     * @var
     */
    private static $http_url;

    /**
     * 路由信息
     * @var array
     */
    private static $route_info = [];

    /**
     * 载入前的路由前缀
     * @var array
     */
    private static $route_url_prefix = [];

    /**
     * 当前请求部分数据
     * @var array
     */
    private static $cache_param_assets = [];

    /**
     * @ 当前的参数
     */
    private static $cache_param;

    /**
     * @ 当前的扩展名
     */
    private static $cache_ext;

    /**
     * @当前的请求方式
     */
    private static $cache_request_method;

    /**
     * 当前的路由前缀
     * @var
     */
    private static $cache_prefix;

    /**
     * 实例化路由
     * @return mixed
     * @throws \Exception
     */
    public static function client(){
        self::$cache_param_assets = pathinfo(Request::$param_url);
        self::request_disassemble();

        if(!isset(self::$route_info[self::$cache_prefix]))
        {
            if(!empty(self::$http_url))
            {
                self::not_route_import();
            }else{
                //加载默认路由
                if(!isset(self::$route_info['leaf_default/'])){
                    throw new \Exception(Lang::get('error_leaf_default_route'));
                }
                self::$cache_prefix = 'leaf_default/';
            }
        }
        self::param_parse();

        return self::$route_info[self::$cache_prefix];
    }


    /**
     * 解析各路由数据
     * @throws \Exception
     */
    private static function param_parse()
    {
        $array_info = self::$route_info[self::$cache_prefix];
        self::request_ext($array_info['ext']);
        self::request_method($array_info['request_method']);
        self::request_param_parse();
    }


    /**
     * 参数解析过滤
     */
    private static function request_param_parse()
    {
        $array_info = self::$route_info[self::$cache_prefix];
        $request_param_arr = array_filter(explode('/',self::$cache_param));
        if(!empty($request_param_arr))
        {
            //定义过的路由参数
            if(!empty($array_info['param']))
            {
                $i=0;
                foreach ($array_info['param'] as $key=>$val)
                {
                    if(empty($array_info['param'][$key]))
                    {
                        $array_info['param'][$key] = new_addslashes($request_param_arr[$i]);
                    }
                    $i++;
                }
                //闭包验证数据
                if(is_object($array_info['function']))
                {
                    $function_param = $array_info['function']($array_info['param']);
                    if($function_param === false){
                        throw new \Exception(Lang::get('error_param_verify').self::$http_url);
                    }
                    $array_info['param'] = $function_param;
                }


            }else{
                //未定义的路由
                $array_info['param'] = new_addslashes($request_param_arr);
            }
        }
        self::$route_info[self::$cache_prefix] = $array_info;
    }


    /**
     * 验证扩展名
     * @param array $ext
     * @throws \Exception
     */
    private static function request_ext(array $ext)
    {
        if(!empty($ext))
        {
            if(!in_array(self::$cache_ext,$ext) && !(Config::get('default_ext')===self::$cache_ext)){
                throw new \Exception(Lang::get('error_extension').self::$cache_ext);
            }

        }else{
            if(!empty(self::$cache_ext)){
                if(Config::get('default_ext')!==self::$cache_ext){
                    throw new \Exception(Lang::get('error_extension').self::$cache_ext);
                }
            }
        }
    }


    /**
     * 验证请求方式
     * @param array $request_method
     * @throws \Exception
     */
    private static function request_method(array $request_method)
    {
        if(!empty($request_method[0]))
        {
            if(!in_array(self::$cache_request_method,$request_method)){
                throw new \Exception(Lang::get('no_request_method').self::$cache_request_method);
            }

        }
    }



    /**
     * 请求的路由数据分解
     * @return bool
     */
    private static function request_disassemble()
    {
        self::$cache_request_method = strtolower(Request::$method);
        if(self::$cache_param_assets['dirname'] !== '\\')
        {
            self::$http_url = self::$cache_param_assets['dirname'];
        }

        if(!empty(self::$cache_param_assets['filename']))
        {
            self::$http_url .= '/'.self::$cache_param_assets['filename'].'/';
            self::$cache_ext = self::$cache_param_assets['extension']??'';
        }

        if(!empty(self::$http_url))
        {
            foreach (self::$route_url_prefix as $val)
            {
                if(strpos(self::$http_url,$val,0) === 0 )
                {
                    self::$cache_prefix = substr($val,1);
                    self::$cache_param = str_replace($val,'',self::$http_url);
                    return true;
                }
            }
            self::$cache_prefix = self::$http_url;
        }
    }








    /**
     *
     * 下半部分代码是导入路由处理逻辑
     *
     *
     *
     * 初始化路由信息
     * @param $alias
     * @param $rule
     * @param array $manage
     */
    public static function init($alias, $rule, array $manage = [])
    {
        if(is_string($alias))
        {
            if(!empty($alias) && !empty($rule)) {
                self::route_import($alias, $rule, $manage);
            }
        }

        if(is_array($alias)){
            if(!empty($alias)){
                foreach($alias as $key=>$val){
                    $arr['method'] = ($val['method']??$rule['method'])?:'';
                    $arr['ext'] = ($val['ext']??$rule['ext'])?:'';

                    if(isset($val['param'])){
                        $arr['param'] = $val['param'];
                    }
                    if(isset($rule['param'])){
                        $arr['param'] = $rule['param'];
                    }
                    $arr['param'] = $arr['param']??[];
                    self::route_import($key,$val[0],$arr);
                }
            }
        }


    }

    /**
     * 处理路由信息并保存
     * @param $alias
     * @param $rule
     * @param array $manage
     * @throws \Exception
     */
    private static function route_import($alias, $rule, array $manage = [])
    {
        $posnum = strpos($alias,'?');
        $route_path = substr($alias,0,$posnum) ?: $alias;
        if($route_path[strlen($route_path)-1] !== '/'){
            $route_path = $route_path.'/';
        }
        if(!isset(self::$route_info[$route_path])){
            $rule_path_method = self::is_controller_method($rule);
            $parse_method = parse_url($rule_path_method[1]);
            $array_route['path'] = $rule_path_method[0];
            $array_route['method'] = $parse_method['path']??'';

            $route_param = '';
            if($posnum){
                $route_param = str_replace('?','',substr($alias,$posnum,strlen($alias)) ?: '');
            }
            $route_param .= ($parse_method['query'] ?? '')?'?'.$parse_method['query'] ?? '':'';
            $array_route['param'] = self::route_param_array($route_param);
            $array_route['request_method'] = explode('|',$manage['method']??'');
            $array_route['ext'] = explode('|',$manage['ext']??'');
            $array_route['function'] = $manage['param']??'';

            self::$route_info[$route_path] = $array_route;
            self::$route_url_prefix[] = '/'.$route_path;
        }

    }

    /**
     * 没有路由设置的情况下解析请求信息
     */
    private static function not_route_import()
    {
        $rule_path_method = self::is_controller_method(self::$http_url);
        if(!isset(self::$route_info[self::$cache_prefix])){
            $array_route['path'] = substr($rule_path_method[0],1);
            $array_route['method'] = $rule_path_method[1];
            $array_route['param'] = [];
            $array_route['request_method'] = [];
            $array_route['ext'] = [];

            $param_url = str_replace('\\','/',$rule_path_method[0]);
            if(!empty($rule_path_method[1])){
                $param_url .= '/'.$rule_path_method[1].'/';
            }
            self::$cache_param = str_replace($param_url,'',self::$http_url);

            self::$route_info[self::$cache_prefix] = $array_route;
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
    private static function route_param_array($rule_param = null)
    {
        if($rule_param){
            $parse_array = parse_url($rule_param);
            $path_array = [];
            if(!empty($parse_array['path']))
            {
                $parse_array_path = explode('/',$parse_array['path']);
                foreach($parse_array_path as $val)
                {
                    $path_array[$val] = '';
                }
            }

            $query_array = [];
            if(!empty($parse_array['query']))
            {
                parse_str($parse_array['query'],$query_array);
            }
            $rele_array = array_merge($path_array,$query_array);
            return $rele_array;
        }

        return '';

    }


}

?>