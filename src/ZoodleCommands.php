<?php

namespace WP_CLI\Zoodle;

use WP_CLI;
use WP_CLI_Command;
use ZipArchive;

class ZoodleCommands extends WP_CLI_Command {

    /**
     * Prints a greeting.
     *
     * ## OPTIONS
     *
     * <name>
     * : The name of the person to greet.
     *
     * [--type=<type>]
     * : Whether or not to greet the person with success or error.
     * ---
     * default: success
     * options:
     *   - success
     *   - error
     * ---
     *
     * ## EXAMPLES
     *
     *     wp hello Newman
     *
     * @when after_wp_load
     */
    public function hello( $args, $assoc_args ) {
        list( $name ) = $args;

        // Print the message with type
        $type = $assoc_args['type'];
        WP_CLI::$type( "Hello, $name!" );
    }


    /**
     * Zips a plugin or theme.
     *
     * ## OPTIONS
     *
     * <entity-type>
     * : The type of entity to zip. (plugin|theme)
     *
     * <entity-name>
     * : The name of the entity to zip.
     *
     * ## EXAMPLES
     *
     *     wp zoodle zip plugin akismet
     *
     * @when before_wp_load
     */
    public function zip( $args, $assoc_args ) {
        list( $entityType, $entityName ) = $args;

        $options = [
            'return' => true,
        ];
        $entityPath = WP_CLI::runcommand( "$entityType path $entityName", $options);

        $entityDir = WP_CLI\Utils\trailingslashit(dirname($entityPath));
        $zipFileDir = _zoodle_get_zoodle_dir();
        $zipFileName = "$entityName.zip";

        $zipFile = _zoodle_zip($entityDir, $zipFileName, $zipFileDir);

        if ($zipFile) {
//            WP_CLI::success( "Zip created successfully." );
//            WP_CLI::success( "Name of Zip File: $zipFileName" );
//            WP_CLI::success( "$entityType, $entityName, $entityPath" );

            $output = [['name' => $zipFileName, 'file' => $zipFileDir . $zipFileName]];
            WP_CLI\Utils\format_items('json', $output, ['name', 'file']);
//            echo PHP_EOL;
        } else {
            WP_CLI::error( "Error zipping file" );
        }
    }

}
