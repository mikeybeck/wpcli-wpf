<?php

function _zoodle_zip($dirToZip, $zipFileName, $zipDir) {
    $rootPath = realpath($dirToZip);

    $zip = new ZipArchive();
    $zip->open($zipDir . $zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    /** @var SplFileInfo[] $files */
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $file)
    {
        // Skip directories (they would be added automatically)
        if (!$file->isDir())
        {
            // Get real and relative path for current file
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);

            // Add current file to archive
            $zip->addFile($filePath, $relativePath);
        }
    }

    $zip->close();

    return $zip;
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

function _zoodle_log($output)
{
    $logFile = _zoodle_get_zoodle_dir() . 'zoodle.log';
    $logMessage = date('Y-m-d H:i:s') . ' ' . print_r($output, true) . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
//    WP_CLI::log($logMessage);
}

function _zoodle_format_output($output, $format = 'json', $fields = []) {
    $status = 'unknown';

    if (is_string($output) && stripos($output, 'error') !== false) {
        $status = 'error';
    }

    if (is_string($output) && stripos($output, 'success') !== false) {
        $status = 'success';
    }

    WP_CLI\Utils\format_items('json', [['status' => $status, 'response' => $output]], ['status', 'response']);
}























//
