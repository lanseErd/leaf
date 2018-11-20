<?php
/**
 * 路由处理
 * Date: 2018/11/19
 * Time: 17:50
 */

class Routers{

    public static $instance;

    public static function http(){

        $options['url']= $_SERVER['PATH_INFO'];

        self::$instance= new self($options);
        var_dump(self::$instance);
        return self::$instance;

//        $http_url = pathinfo($_SERVER['PATH_INFO'],PATHINFO_DIRNAME);
//        $http_path = $this->is_controller_method($http_url);
//
//        return $_SERVER['PATH_INFO'];
//
//        if($http_path){
//            echo 444;
//        }else{
//            echo 333;
//            //没有该接口
//            file_put_contents("fff.txt", "www.".PHP_EOL, FILE_APPEND);
//        }
    }

    public static function uuyy(){
        return self::$instance;
    }

    /**
     *
     * @param $http_path
     */
    public function is_controller_method($http_path)
    {
        $array_path = explode('/',$http_path);
        $is = false;
        for($i=0;$i<count($array_path);$i++){
            if($i==1){
                $is = 1;
            }
        }
        return $is;
    }

}



$obj = new Routers();

$ress = $obj::http();



$ress;

?>
