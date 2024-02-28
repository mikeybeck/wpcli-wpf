<?php

function _wpfoundry_zip($dirToZip, $zipFileName, $zipDir) {
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

function _wpfoundry_get_root_dir(bool $useDir = false) {
    if ($useDir) {
        $path = __DIR__;
    } else {
        $path = getcwd(); // __DIR__ returns the directory of the cli package, not the current working directory
    }

//    WP_CLI::log("getcwd: $path");
//    WP_CLI::log("DIR: " . __DIR__);
    $count = 0;
    while (true) {
        if (file_exists($path . "/wp-config.php")) {
            return \WP_CLI\Utils\trailingslashit($path);
        }

        $path = dirname($path);

        WP_CLI::log("Checking path: $path");
        if ($count++ > 10 || $path === '/') {
            WP_CLI::error("Could not find root directory using getcwd().  Will try using __DIR__.");
            if ($useDir) {
                return false;
            }

            return _wpfoundry_get_root_dir(true);
        }
    }
}

function _wpfoundry_get_wpfoundry_dir() {
    $dir = _wpfoundry_get_root_dir();
    if (!$dir) {
        return false;
    }

    return _wpfoundry_get_root_dir() . 'wp-content/wpfoundry/';
}

function _wpfoundry_log($output)
{
    $logFile = _wpfoundry_get_wpfoundry_dir() . 'wpfoundry.log';
    $logMessage = date('Y-m-d H:i:s') . ' ' . print_r($output, true) . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
//    WP_CLI::log($logMessage);
}

function _wpfoundry_format_output($output, $format = 'json', $fields = []) {
    $status = 'unknown';
//    _wpfoundry_log('Output: ' . print_r($output, true));
//    WP_CLI::log('Output: ' . print_r($output, true));

    if (is_string($output) && stripos($output, 'error') !== false) {
        $status = 'error';
//        _wpfoundry_log($output);
//        WP_CLI::error($output);
    }

    if (is_string($output) && stripos($output, 'success') !== false) {
        $status = 'success';
//        _wpfoundry_log($output);
//        WP_CLI::success($output);
    }

//    _wpfoundry_log('Status: ' . $status);
//    WP_CLI::log('Status: ' . $status);

    WP_CLI\Utils\format_items('json', [['status' => $status, 'response' => $output]], ['status', 'response']);
}























//
