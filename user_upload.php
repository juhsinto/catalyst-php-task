<?php

// Please run `composer dump-autoload -o` if directory 'vendor/' not present
require "vendor/autoload.php";

use CatalystTask\InitializeEmptyTable as InitializeEmptyTable;
use CatalystTask\Utilities as Utilities;
use CatalystTask\InsertIntoTable as InsertIntoTable;

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

    /* this stub assumes that the database `testdb` is created, and input user has access to `testdb */
    /* If not ; then follow the below steps
     * $ psql
     * # create db
     * CREATE DATABASE testdb;
     *
     * # grant privileges to `tester` (ASSUMING `tester` has insert privilege for this script)
     * GRANT ALL PRIVILEGES ON DATABASE "testdb" to tester;
     */
if ($results["create_table"]) {
    // not all params entered correctly
    if($results["user"] == ""
        || $results["password"] == ""
        || $results["host"] == "") {
        echo "Not all parameters were entered correctly. Please run the script with --help \n";

    }
    // user, password and host is specified
    elseif($results["password"] != ""
        && $results["host"] != ""
        && $results["user"] != "") {

        try {
            $tableCreator = new InitializeEmptyTable($results["user"], $results["password"], $results["host"]);
            echo "Table `users` was created ! " . "\n";

        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
        }
    }
}
// just doing a dry run
elseif ($dryRunEnabled && $results["inputFile"] != "") {

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
} elseif ($results["user"] != ""
            && $results["password"] != ""
            && $results["host"] != ""
            && $results["inputFile"] != "") {
        // user, password, host and input file specified

        $util = new Utilities();

        try {
            $csv = $util->readCSV($results["inputFile"]);

            $validator = $util->validator($csv);

            if($validator) {
                echo "Input file seems to be OK. \n";
                $rowInserter = new InsertIntoTable($results["user"], $results["password"], $results["host"]);
                $rowInserter->insertValidRows($csv);
            } else {
                echo "There was a problem with validity of the data. \n";
            }

        }  catch(\Exception $e) {
            echo $e->getMessage() . "\n";
        }
} elseif($results["inputFile"] != "" && ($results["password"] == ""
    || $results["host"] == ""
    || $results["inputFile"] == "")) {
    echo "Not all parameters were entered correctly. Please run the script with --help \n";

} else {
    if ($results["create_table"] == "" && $results["inputFile"] == "") {
        echo "No file was specified. Please run the script with --help \n";
    }
}