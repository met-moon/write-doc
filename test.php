<?php

require_once 'vendor/autoload.php';

echo $salt = \App\Services\UserService::salt();

echo PHP_EOL.$password = \App\Services\UserService::encrypt('123456', $salt);
