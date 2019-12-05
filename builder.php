<?php
/**
 * @date 2018-10-19 19:57
 */
require './vendor/autoload.php';

use Moon\WriteDoc\WriteDoc;

$writer = new WriteDoc(__DIR__);

$project = $_SERVER['argv'][1] ?? die("Usage:\n\tphp builder.php [project]\n");

echo $writer->build('demo');

