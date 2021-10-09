<?php
/**
 * Date: 2019-12-05
 * Time: 11:44
 */

require __DIR__.'/../vendor/autoload.php';

use MetMoon\WriteDoc\WriteDoc;

$writer = new WriteDoc(dirname(__DIR__));
echo $writer->show('demo', 'index');