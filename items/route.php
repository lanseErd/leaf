<?php
/**
 * 路由处理
 * Date: 2018/11/19
 * Time: 17:50
 */

use Leaf\Router;

Router::init(
    [
        //默认方法不可为空
        'leaf_default'=>'index/index/init',
        'mem/?id/?userid'=>['member/member/add','method'=>'get','ext'=>'html','param'=>
                function(){
                    return 1;
                }
            ]
    ],
    ['method'=>'get','ext'=>'html','param'=>function($all){}]
);

?>
