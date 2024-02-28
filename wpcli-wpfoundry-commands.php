<?php

namespace WP_CLI\wpfoundry;

use WP_CLI;

if ( ! class_exists( '\WP_CLI' ) ) {
	return;
}

$wpcli_wpfoundry_autoloader = __DIR__ . '/vendor/autoload.php';

if ( file_exists( $wpcli_wpfoundry_autoloader ) ) {
	require_once $wpcli_wpfoundry_autoloader;
}

require_once __DIR__ . '/src/wpfoundry-functions.php';

// Create the wpfoundry directory
if ( ! file_exists( _wpfoundry_get_wpfoundry_dir() ) ) {
    if (!mkdir($concurrentDirectory = _wpfoundry_get_wpfoundry_dir()) && !is_dir($concurrentDirectory)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
    }
}

WP_CLI::add_command( 'wpfoundry', WPFoundryCommands::class );
