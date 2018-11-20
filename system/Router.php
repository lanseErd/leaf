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

    public function init(array $alias,array $rule)
    {
        //var_dump(Request::$param_url['dirname']);
        //$request = new Request();

        if(!empty($alias))
        {
            $http_url = pathinfo($_SERVER['REQUEST_URI'],PATHINFO_DIRNAME);
            foreach($alias as $key=>$val)
            {
                $is_param = substr($key,0,strpos($key,'?'));
                if($is_param){
                    $http_path = $is_param;
                }else{
                    $http_path = $key;
                }

                //判断有没有申明该路由
                if(substr($http_url,0,strlen($http_path)) === $http_path){
                    self::$http[$val[0]] = 1;
                }else{
                    var_dump($http_url);
                    $http_path = $this->is_controller_method($http_url);
                    if($http_path){
                        $http_path = str_ireplace(DIRECTORY_SEPARATOR,'/',$http_path);
                        self::$http[$http_path] = 1;
                    }else{
                        //没有该接口
                        file_put_contents("ddd.txt", "222.".PHP_EOL, FILE_APPEND);
                        throw new \Exception("没有该地址ss");//使用throw抛出异常
                    }
                }
            }

        }

        //
    }





    public function route()
    {
        //$this->request = new Request();
    }


    /**
     * 验证未定义路由方法是否存在
     * @param $http_path
     */
    public function is_controller_method($http_path)
    {
        $array_path = explode('/',$http_path);
        $i=0;
        //while($i<count($array_path)){
//            if(file_exists('controller'.DIRECTORY_SEPARATOR.$array_path[$i].'.php')){
//                return $array_path[$i].DIRECTORY_SEPARATOR.$array_path[$i+1];
//            }else if(file_exists('controller'.DIRECTORY_SEPARATOR.$array_path[$i])){
//                if(!empty($array_path[$i+1])){
//                    $array_path[$i+1] = $array_path[$i].DIRECTORY_SEPARATOR.$array_path[$i+1];
//                }
//            }



            //$i++;
        //}


        for($i=0;$i<count($array_path);$i++){
            if($i==1){
                return true;
            }
        }

        return false;
    }

}
