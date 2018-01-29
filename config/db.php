<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2018/1/29
 * Time: 13:43
 */

return [
    'dsn' => 'mysql:host=localhost;dbname=write_doc;port=3306;charset=utf8',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8',
    'tablePrefix' => '',
    'emulatePrepares' => false,
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
];