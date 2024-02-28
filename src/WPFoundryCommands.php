<?php

namespace WP_CLI\wpfoundry;

use WP_CLI;
use WP_CLI_Command;

class WPFoundryCommands extends WP_CLI_Command {

    public function __construct() {
        @set_exception_handler([$this, 'exception_handler']);
//        throw new Exception('DOH!!');
    }

    public function exception_handler($exception) {
        WP_CLI::error('WPFoundryCommands::exception_handler(): ' . $exception->getMessage(), false);

        $output = [
            [
                'exception' => true,
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'message' => $exception->getMessage(),
            ]
        ];

        WP_CLI\Utils\format_items('json', $output, ['exception', 'file', 'line', 'message']);
        _wpfoundry_log($output);
    }

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
        [ $name ] = $args;

        // Print the message with type
        $type = $assoc_args['type'];
        WP_CLI::$type( "Hello, $name!" );
    }

    /**
     * Prints the wpfoundry version.
     *
     * ## EXAMPLES
     *
     *     wp wpfoundry version
     *
     * @when before_wp_load
     */
    public function version() {
        WP_CLI\Utils\format_items('json', [['version' => '1.0.0']], ['version']);
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
     *     wp wpfoundry zip plugin akismet
     *
     * @when before_wp_load
     */
    public function zip( $args, $assoc_args ) {
        [$entityType, $entityName] = $args;

        _wpfoundry_log('ZIP1');

        $options = [
            'return' => true,
        ];
        $entityPath = WP_CLI::runcommand("$entityType path $entityName", $options);


        _wpfoundry_log('ZIP2');
        $entityDir = WP_CLI\Utils\trailingslashit(dirname($entityPath));
        $zipFileDir = _wpfoundry_get_wpfoundry_dir();
        $zipFileName = "$entityName.zip";

        _wpfoundry_log('ZIP3');
        $zipFile = _wpfoundry_zip($entityDir, $zipFileName, $zipFileDir);

        _wpfoundry_log('ZIP4');
        if ($zipFile) {
            _wpfoundry_log('ZIP5');
//            WP_CLI::success( "Zip created successfully." );
//            WP_CLI::success( "Name of Zip File: $zipFileName" );
//            WP_CLI::success( "$entityType, $entityName, $entityPath" );

            $output = [['name' => $zipFileName, 'file' => $zipFileDir . $zipFileName]];
            WP_CLI\Utils\format_items('json', $output, ['name', 'file']);
//            echo PHP_EOL;
        } else {
            WP_CLI::error("Error zipping file");
        }
    }

    /**
     * Attempts to execute a WP CLI command and format the output.
     * Useful for commands which do not have a --format option.
     *
     * @param $args
     * @return void
     */
    public function executeAndFormat($args) {
//        WP_CLI::success("Test success");
//        WP_CLI::error("Test error");
//        $assoc_args = ['format' => 'json'];
//        $response = (new \WP_CLI\Formatter($assoc_args))->transform_item_values_to_json([['name' => 'test', 'file' => 'test.zip']]);
//        WP_CLI::success($response);

        [$command] = $args;

        try {
            $response = WP_CLI::runcommand($command, [
                'return' => true, // Note that this doesn't always work when there is an error.
                'launch' => false,
                'exit_error' => false,
            ]);

            if (trim($response) === '') {
//                WP_CLI::error("No response from command: $command");

                // Run again with return set to false to see if there is any output.
                // Fudge the output a bit to make it like JSON, since I can't figure out any other way to
                // get the output.
                echo PHP_EOL;
                echo '[{"status":"error","response":"No response from command: ' . $command . '","error": "';

                WP_CLI::runcommand($command, [
                    'return' => false,
                    'launch' => false,
                    'exit_error' => false,
                ]);

                echo '"}]';

                return;
            }

        } catch (\Exception $e) {
            WP_CLI::error($e->getMessage());
        }

        _wpfoundry_format_output($response);
    }

}
