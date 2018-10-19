<?php
/**
 * @date 2018-10-19 19:57
 */
require './vendor/autoload.php';

use Moon\WriteDoc\WriteDoc;

$writer = new WriteDoc(__DIR__);

echo $writer->show('demo', 'index');