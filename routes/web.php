<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2018/1/11
 * Time: 22:05
 */

Route::get('/', 'IndexController::index');

Route::get('login', 'IndexController::login');

Route::get('say/{something}', 'IndexController::say');