<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2018/1/11
 * Time: 17:38
 */

require '../vendor/autoload.php';

$app = new \Moon\Application('../');
$app->enableDebug();
$app->run();
