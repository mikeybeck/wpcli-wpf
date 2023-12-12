<?php

namespace WP_CLI\Zoodle;

use WP_CLI;

if ( ! class_exists( '\WP_CLI' ) ) {
	return;
}

$wpcli_zoodle_autoloader = __DIR__ . '/vendor/autoload.php';

if ( file_exists( $wpcli_zoodle_autoloader ) ) {
	require_once $wpcli_zoodle_autoloader;
}

require_once __DIR__ . '/src/zoodle-functions.php';

WP_CLI::add_command( 'zoodle', ZoodleCommands::class );

//WP_CLI::add_command( 'hello-2', ZoodleCommands::class );
