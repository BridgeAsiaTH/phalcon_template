<?php

function base_path($path = '')
{
    return dirname(__DIR__) . ($path ? DIRECTORY_SEPARATOR . $path : $path);
}

function app_path($path = '')
{
    return dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
}

function env($key, $default = null)
{
    $value = getenv($key);
    if ($value === false) {
        return $default;
    }
    switch (strtolower($value)) {
        case 'true':
            return true;
        case 'false':
            return false;
        case 'empty':
            return '';
        case 'null':
            return null;
    }
    return $value;
}

function toArrayByField($records, $fieldName)
{
    $output = [];
    foreach ($records as $row) {
        if (isset($row->{$fieldName})) {
            $output[] = $row->{$fieldName};
        }
    }
    return $output;
}

function getMinifiedPath($file, $filteringFileType, $suffix)
{
    return str_replace('.'.$filteringFileType, $suffix, $file);
}

function getDirContents($dir, &$results = [], $filteringFileType = 'txt')
{
    $items = scandir($dir);
    foreach ($items as $value) {
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
        if (!is_dir($path)) {
            $fileExtension = pathinfo($path, PATHINFO_EXTENSION);
            if ($fileExtension === $filteringFileType) {
                $results[] = $path;
            } else {
                continue;
            }
        } elseif ($value !== '.' && $value !== '..') {
            // Recursively
            getDirContents($path, $results, $filteringFileType);
        }
    }
    return $results;
}

function getResourceFilename($file, bool $cacheBuster = false)
{
    // If real file exists, then try to generate md5 versioning.
    $version = '';
    if ($cacheBuster) {
        $fullPath = $file;
        if (!file_exists($fullPath)) {
            $fullPath = base_path('public').DIRECTORY_SEPARATOR.$file;
        }
        if (file_exists($fullPath)) {
            $version = '.'.getFileVersioning($fullPath);
        }
    }
    // Else version could be empty string

    $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
    $fileExtensionWithPrependDot = '.'.$fileExtension;
    if (env('ENV') === 'prod' || env('ENV') === 'staging') {
        if ($fileExtension) {
            $suffix = '.min.'.$fileExtension;
            $hasMinifiedSuffixAlready = strpos(substr($file, -strlen($suffix)), $suffix) !== false;
            $fullSuffix = $version.$suffix;
            if (!$hasMinifiedSuffixAlready) {
                // style/bootstrap.css -->
                // style/bootstrap.min.css OR
                // style/bootstrap.[md5:10].min.css
                $file = substr_replace($file, $fullSuffix, -strlen($fileExtensionWithPrependDot), strlen($fullSuffix));
            } else {
                // style/bootstrap.min.css -->
                // style/bootstrap.min.css OR
                // style/bootstrap.[md5:10].min.css
                $file = substr_replace($file, $fullSuffix, -strlen($suffix), strlen($fullSuffix));
            }
        }
    } else {
        // No minify
        if ($version) {
            $fullSuffix = $version.$fileExtensionWithPrependDot;
            $file = substr_replace($file, $fullSuffix, -strlen($fileExtensionWithPrependDot), strlen($fullSuffix));
        }
    }
    return $file;
}

function getFileVersioning(string $file, int $versionLength = 10)
{
    return substr(md5(file_get_contents($file)), 0, $versionLength) ;
}
