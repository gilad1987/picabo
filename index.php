<?php
// define('BASE_PATH', basename(dirname(__FILE__)));
// if($_SERVER['SERVER_PORT'] != "8066" && !isset($_GET['gt_test'])){
// 	die();
// }
require_once 'Api/index.php';
App_Dispatcher::run();
