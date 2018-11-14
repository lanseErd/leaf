<?php

// 定义应用目录
define('SYS_PATH', __DIR__ . '/../system/');


//请求入口
require_once SYS_PATH.'Load.php';

//加载composer
require '../vendor/autoload.php';

Leaf\Load::initialize();

Leaf\Error::init();

new Leaf\Request();

?>