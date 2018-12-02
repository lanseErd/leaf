<?php

namespace Leaf;

class Leaf{

    public static $action_url;
    public static $action_method;
    public static $action_param;

    /**
     * 运行
     * @throws \ReflectionException
     */
    public static function run()
    {
        new Request();
        include APP_PATH . "route.php";
        $action_resource =  Router::client();
        self::$action_url = $action_resource['path'];
        self::$action_method = $action_resource['method']?:'';
        self::$action_param = $action_resource['param']?:[];

        self::controller();
    }

    /**
     * 加载控制器
     * @throws \ReflectionException
     */
    public static function controller()
    {
        $path = 'Items'.DIRECTORY_SEPARATOR.'controller'.DIRECTORY_SEPARATOR.str_replace('/',DIRECTORY_SEPARATOR,self::$action_url);
        $class = new \ReflectionClass($path);
        $constructor = $class->newInstanceArgs();
        //加载方法
        $default_method = 0;
        if(!empty(self::$action_method) && $class->hasMethod(self::$action_method)){
            $method = new \ReflectionMethod($constructor, self::$action_method);
            if($method->isPublic()){
                $method->invoke($constructor,self::$action_param);
            }else{
                $default_method = true;
            }
        }else{
            $default_method = true;
        }

        //没有方法就载入默认方法
        if($default_method){
            if($class->hasMethod('init')){
                $constructor->init();
            }else{
                throw new \Exception(Lang::get('empty_leaf_init'));
            }

        }

    }
}

?>