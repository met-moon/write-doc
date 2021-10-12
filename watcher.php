<?php
/**
 * User: heropoo
 * Datetime: 2021/10/13 00:05
 */

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Finder\Finder;
use Yosymfony\ResourceWatcher\Crc32ContentHash;
use Yosymfony\ResourceWatcher\ResourceWatcher;
use Yosymfony\ResourceWatcher\ResourceCachePhpFile;

$project = $_SERVER['argv'][1] ?? die("Usage:\n\tphp watcher.php [project]\n");

$project_path = __DIR__ . '/docs/' . $project;

if (!is_dir($project_path)) {
    die("$project is not exists!");
}

$finder = new Finder();
$finder->files()
    ->name('*.md')
    ->in($project_path);

$hashContent = new Crc32ContentHash();
$resourceCache = new ResourceCachePhpFile(__DIR__ . '/tmp/watcher-cache-' . $project . '.php');
$watcher = new ResourceWatcher($resourceCache, $finder, $hashContent);
$watcher->initialize();

while (1) {
//    $result = $watcher->findChanges();
//    $result->getUpdatedFiles());
    if ($watcher->findChanges()->hasChanges()) {
        echo exec('php builder.php ' . $project, $output, $result_code) . PHP_EOL;
        //echo "\n".$output."\n";
    }
    echo "\r>_< ";
    sleep(1);
}