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
    public function __construct($user, $password, $host) {

        try {
            Connection::get()->connect($user, $password, $host);
//            echo 'A connection to the PostgresSQL database sever has been established successfully. \n';

            // connect to the PostgresSQL database
            $pdo = Connection::get()->connect($user, $password, $host);


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