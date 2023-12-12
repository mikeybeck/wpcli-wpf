<?php

function _zoodle_zip($dirToZip, $zipFileName, $zipDir) {
    $zipFile = new ZipArchive;

    if ($zipFile->open($zipDir . $zipFileName, ZipArchive::CREATE) === true) {

        $dir = opendir($dirToZip);

        while ($file = readdir($dir)) {
            if (is_file($dirToZip . $file)) {
                $zipFile->addFile($dirToZip . $file, $file);
            }
        }

        $zipFile->close();

        return $zipFile;
    }

    WP_CLI::error("Error");
    return false;
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
