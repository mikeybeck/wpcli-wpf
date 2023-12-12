<?php

#WP_CLI::log( "Script running.." );

//$options = array(
//            'return' => true,
//        );
//$entityName = "akismet";
//$entityPath = WP_CLI::runcommand( "plugin path $entityName", $options);
//
//$pathdir = plugin_dir_path($entityPath);
//
//$zipcreated = "$entityName.zip";
//
//// Create new zip class
//$zip = new ZipArchive;
//
//if($zip -> open($zipcreated, ZipArchive::CREATE ) === TRUE) {
//
//    // Store the path into the variable
//    $dir = opendir($pathdir);
//
//    while ($file = readdir($dir)) {
//        if (is_file($pathdir . $file)) {
//            $zip->addFile($pathdir . $file, $file);
//        }
//    }
//
//    $zip->close();
//    echo "Zip created successfully.";
//    echo "<br>";
//    echo "Name of Zip File: $zipcreated";
//    echo var_dump($zip);
//}

#WP_CLI\Utils\format_items( 'json', [['name' => 'test.zip']], ['name'] );

//echo _zoodle_get_root_dir() . PHP_EOL;
//$zipDir = WP_CLI\Utils\trailingslashit('/zoodle/');
//echo $zipDir;
#echo PHP_EOL;
#WP_CLI::log( "Script finished." );
