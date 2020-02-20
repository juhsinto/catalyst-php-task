<?php
/**
 * Created by PhpStorm.
 * User: jacinto
 * Date: 19/2/20
 * Time: 8:13 PM
 */

namespace CatalystTask;

use CatalystTask\Connection as Connection;
use CatalystTask\CreateTable as CreateTable;

class InitializeEmptyTable
{
    public function __construct() {

        try {
            Connection::get()->connect();

//            echo 'A connection to the PostgresSQL database sever has been established successfully. \n';

            // connect to the PostgresSQL database
            $pdo = Connection::get()->connect();


            // create an instance of the table creator
            $tableCreator = new CreateTable($pdo);

            // create table
            $tableCreator->createTable();

        } catch (\PDOException $e) {
            echo "Could not connect to database: " . $e->getMessage() . "\n";
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
        }

    }


}