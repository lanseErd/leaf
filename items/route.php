<?php
/**
 * 路由处理
 * Date: 2018/11/19
 * Time: 17:50
 */


Request::init([
    'mem/?id/?userid'=>['member/member/add','method'=>'get','ext'=>'html','param'=>function(){}]
]);

?>
