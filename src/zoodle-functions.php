<?php

// Broken.. needs fixing
function _zoodle_zip($dirToZip, $zipFileName, $zipDir, $zipFile = null) {
    if ($zipFile === null) {
        $zipFile = new ZipArchive;
        if ($zipFile->open($zipDir . $zipFileName, ZipArchive::CREATE) !== true) {
            WP_CLI::error("Error");
            return false;
        }
    }

    $dir = opendir($dirToZip);
    while ($file = readdir($dir)) {
        if ($file === '.' || $file === '..') continue;
        $path = $dirToZip . '/' . $file;
        if (is_dir($path)) {
            _zoodle_zip($path, $zipFileName, $zipDir, $zipFile);
        } else if (is_file($path)) {
            $zipFile->addFile($path, str_replace($zipDir, '', $path));
        }
    }

    if ($zipFile !== null) {
        $zipFile->close();
    }

    return $zipFile;
}

function _zoodle_get_root_dir() {
    $path = __DIR__;
    $count = 0;
    while (true) {
        if (file_exists($path . "/wp-config.php")) {
            return \WP_CLI\Utils\trailingslashit($path);
        }

        $path = dirname($path);

        if ($count++ > 10) {
            WP_CLI::error("Could not find root directory");
            WP_CLI::error("Checking path: $path");
            return false;
        }
    }
}

function _zoodle_get_zoodle_dir() {
    $dir = _zoodle_get_root_dir();
    if (!$dir) {
        return false;
    }

    return _zoodle_get_root_dir() . 'zoodle/';
}
