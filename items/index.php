<?php

// 定义应用目录
define('APP_PATH', __DIR__.DIRECTORY_SEPARATOR);
define('SYS_PATH', __DIR__ . '/../system/');


//文件加载器
require_once SYS_PATH.'Load.php';

//加载composer
require '../vendor/autoload.php';



Leaf\Load::initialize();


Leaf\Error::init();

$cns = load_file('configs/config');
var_dump($cns);
new Leaf\Request();

?>