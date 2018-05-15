<?php
error_reporting(E_ALL);

require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

$classPrefix = 'MatthiasMullie\Minify';
$rootPath = __DIR__.DIRECTORY_SEPARATOR.'public';
$filteringFileType = 'css';
$operator = $classPrefix.'\CSS';
minify($rootPath, $filteringFileType, $operator);

$filteringFileType = 'js';
$operator = $classPrefix.'\JS';
minify($rootPath, $filteringFileType, $operator);

function minify($rootPath, $filteringFileType, $operator)
{
    $suffix = '.min.'.$filteringFileType;
    $files = [];
    getDirContents($rootPath, $files, $filteringFileType);
    foreach ($files as $file) {
        if (strpos($file, $suffix) === false) {
            // Minify target
            $minifier = new $operator($file);
            $minifiedPath = getMinifiedPath($file, $filteringFileType, $suffix);
            $minifier->minify($minifiedPath);
            echo $minifiedPath . ' is created.'.PHP_EOL;
        }
    }
}
