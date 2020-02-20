<?php

// Please run `composer dump-autoload -o` if directory 'vendor/' not present
require "vendor/autoload.php";

use CatalystTask\InitializeEmptyTable as InitializeEmptyTable;
use CatalystTask\Utilities as Utilities;

$getopts = new Fostam\GetOpts\Handler();

$getopts->addArgument('inputFile')
    ->name('input-file');

$getopts->addOption('create_table')
    ->short('c')
    ->long('create_table')
    ->description('create_table - only create the table');

$getopts->addOption('dry_run')
    ->short('d')
    ->long('dry_run')
    ->description('dry_run - only test the input file');

$getopts->addArgument('user')
    ->name('user');

$getopts->addArgument('password')
    ->name('password');

$getopts->addArgument('host')
    ->name('host');


$getopts->parse();
$results = $getopts->get();




$dryRunEnabled = False;
if ($results["dry_run"] == 1) {
    $dryRunEnabled = True;
}

// just doing a dry run
if ($dryRunEnabled && $results["inputFile"] != "") {
//    echo "Dry run  enabled \n";

     $util = new Utilities();

     try {
         $csv = $util->readCSV($results["inputFile"]);

         $validator = $util->validator($csv);

         if($validator) {
             echo "Input file seems to be OK. \n";
         }

     }  catch(\Exception $e) {
        echo $e->getMessage() . "\n";
     }
} else {
    if ($results["inputFile"] == "") {
        echo "No file was specified. Please run the script with --help \n";
    }
}

/* testing the command line args




// TODO need to implement logic such that if either one is missing, then print message
if ($results["user"]) {
    echo "postgres username is: " . $results["user"] . "\n";
}

if ($results["password"]) {
    echo "postgres password is: " . $results["password"] . "\n";
}

if ($results["host"]) {
    echo "postgres username is: " . $results["host"] . "\n";
}
*/

//var_dump($results);

/* this stub assumes that the database `testdb` is created, and input user has access to `testdb */
/*  info for troubleshooting
 * $ psql
 * # create db
 * CREATE DATABASE testdb;
 *
 * # grant privileges to `tester`
 * GRANT ALL PRIVILEGES ON DATABASE "testdb" to tester;
 */
/* TODO pass the user, password, host to the connect db fn  */
/* TODO - exception handling when user does not have privileges
if ($results["create_table"]) {
    try {
        $tableCreator = new InitializeEmptyTable();
        echo "Table `users` was created ! " . "\n";

    } catch (\Exception $e) {
        echo $e->getMessage() . "\n";
    }
}
*/