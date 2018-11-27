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
                function($all){
                    //验证成功返回验证过的$all，否则返回false
                    //$all['id'] = intval($all['id']);
                    //$all['userid'] = intval($all['userid']);
                    return $all;
                }
            ]
    ],
    ['method'=>'get','ext'=>'html','param'=>function($all){}]
);

?>
