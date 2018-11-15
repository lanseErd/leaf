<?php

// 定义应用目录
define('APP_PATH', __DIR__.DIRECTORY_SEPARATOR);
define('SYS_PATH', __DIR__ . '/../system/');


//加载composer
require '../vendor/autoload.php';

//文件加载器
require_once SYS_PATH.'Load.php';

//自动加载
Leaf\Load::initialize();

//注册配置
Leaf\Config::set(include APP_PATH.'configs/config.php');

//注册异常
Leaf\Error::init();

$rru = Leaf\Config::get('logger');


new Leaf\Request();

?>