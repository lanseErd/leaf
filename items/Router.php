<?php
/**
 * 路由处理
 * Date: 2018/11/19
 * Time: 17:50
 */


class Routers{

    public function http(){
        $http_url = pathinfo($_SERVER['REQUEST_URI'],PATHINFO_DIRNAME);
        var_dump($_SERVER);
        $http_path = $this->is_controller_method($http_url);

        if($http_path){
            echo 444;
        }else{
            echo 333;
            //没有该接口
            file_put_contents("fff.txt", "www.".PHP_EOL, FILE_APPEND);
        }
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

$obj->http();

?>
