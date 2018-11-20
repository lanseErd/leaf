<?php

namespace Leaf;
use Leaf\Router;
class Leaf{

    public static function run()
    {
        //(new Router())->route();
        //new Request();
        include APP_PATH."router.php";

    }
}

?>