<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2017/3/8
 * Time: 15:19
 */
return [
    'default'=> 'mysql',

    'connections'=>[
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => 'localhost:3306',
            'database'  => 'write_doc',
            'username'  => 'root',
            'password'  => 'root',
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],
        'sqlite' => [
            'driver' => 'sqlite',
            'database' => runtime_path('sqlite/write-doc.sqlite'),
            'prefix' => '',
        ],
    ]
];