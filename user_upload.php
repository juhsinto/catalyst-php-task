<?php

// Please run `composer dump-autoload -o` if directory 'vendor/' not present
include "vendor/autoload.php";

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



/* testing the command line args */

//var_dump($results);

if ($results["inputFile"]) {
    echo "input file path is: " . $results["inputFile"] . "\n";
}


$dryRunEnabled = False;
if ($results["dry_run"] == 1) {
    $dryRunEnabled = True;
}

if (!$dryRunEnabled) {
    echo "Dry run not enabled \n";
} else {
    echo "Dry run  enabled \n";
}


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