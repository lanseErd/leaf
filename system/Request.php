<?php
/**
 * 请求处理
 * Date: 2018/11/13
 * Time: 23:10
 */

namespace Leaf;

class Request{


    /**
     * Request constructor.
     */
    public function __construct()
    {
        // 模拟Fatal error错误
        //test();
        //var_dump($rre);
        //var_dump($rres);

        // 模拟用户产生ERROR错误
        //trigger_error('zyf-error', E_USER_ERROR);

        // 模拟语法错误
        //var_dump(23+-+);

        // 模拟Notice错误
        //echo $f;
        //new uudd();

        // 模拟Warning错误
        //echo '123';
        //ob_flush();
        //flush();
        //header("Content-type:text/html;charset=gb2312");
    }
}


?>