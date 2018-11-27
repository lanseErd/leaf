<?php

namespace Leaf;
use Leaf\Router;
class Leaf{

    public static $action_url;
    public static $action_method;

    public static function run()
    {
        new Request();
        include APP_PATH . "route.php";
        $action_resource =  Router::external();
        self::$action_url = $action_resource[0];
        self::$action_method = $action_resource[1];
        self::execute();
    }

    public static function execute()
    {
        $path = 'Items'.DIRECTORY_SEPARATOR.'controller'.DIRECTORY_SEPARATOR.str_replace('/',DIRECTORY_SEPARATOR,self::$action_url);
        $class = new \ReflectionClass($path);
        $constructor = $class->newInstanceArgs();

        $default_method = 0;

        if(!empty(self::$action_method) && $class->hasMethod(self::$action_method)){
            $method = new \ReflectionMethod($constructor, self::$action_method);
            if($method->isPublic()){
                $method->invoke($constructor);
            }else{
                $default_method = true;
            }
        }else{
            $default_method = true;
        }

        //没有方法就载入默认方法
        if($default_method){
            $constructor->init();
        }

    }
}

?>