<?php
/**
 * Created by PhpStorm.
 * User: jacinto
 * Date: 19/2/20
 * Time: 8:13 PM
 */

namespace CatalystTask;

use CatalystTask\Connection as Connection;
use CatalystTask\DataStore as DataStore;
use CatalystTask\Utilities as Utilities;

class InsertIntoTable
{

    private $pdo;
    private $dataStore;

    /**
     * Check if a table exists in the current database.
     * FROM - https://stackoverflow.com/a/14355475/1872088
     *
     * @param PDO $pdo PDO instance connected to a database.
     * @param string $table Table to search for.
     * @return bool TRUE if table exists, FALSE if no table found.
     */
    function tableExists($pdo, $table) {

        // Try a select statement against the table
        // Run it in try/catch in case PDO is in ERRMODE_EXCEPTION.
        try {
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);
            $result = $pdo->query("SELECT 1 FROM $table LIMIT 1");
        } catch (Exception $e) {
            // We got an exception == table not found
            return FALSE;
        }

        // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
        return $result !== FALSE;
    }

    public function __construct($user, $password, $host) {



        try {
            Connection::get()->connect($user, $password, $host);

//            echo 'A connection to the PostgresSQL database sever has been established successfully. \n';

            // connect to the PostgresSQL database
            $pdo = Connection::get()->connect($user, $password, $host);

            $this->pdo = $pdo;

            // create an instance of the table creator
            $pdo_dataStore = new DataStore($pdo);

            $this->dataStore  = $pdo_dataStore;


        } catch (\PDOException $e) {
            echo "Could not connect to database: " . $e->getMessage() . "\n";
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
        }

    }

    public function insertRowIntoUsers($name, $surname, $email) {


        try {
            $this->dataStore->insertRow($name, $surname, $email);
//            echo "Row should have got inserted ! \n";
        } catch (\PDOException $e) {
            echo "Encountered an issue while inserting row into the database table: \n" . $e->getMessage() . "\n";
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
        }
    }


    public function insertValidRows($rows) {

        $util = new Utilities();

        try {

            // check if users table exists!
            if($this->tableExists($this->pdo, "users")) {
                $row = 1;
                foreach ( $rows as $columns ) {

                    // [ASSUMPTION] skip first row, as it contains headers
                    if ($row == 1) {
                        $row++;
                        continue;
                    }

                    $name = $util->stringFormatter($columns[0]);
                    $surname = $util->stringFormatter($columns[1]);
                    $email = $util->removeExclamations(strtolower(trim($columns[2])));

                    // skip blank lines
                    if ($name == "" && $surname == "" && $email == "") {
                        continue;
                    }


                    $this->insertRowIntoUsers($name, $surname, $email);

                }

                echo "Rows were successfully inserted. \n";

            } else {

                echo "Table does not exist! Please re-run the script with the --create-table flag!. \n";
                echo "Please run the script with --help for more information \n";
                return false;
            }
           
        } catch (\PDOException $e) {
            echo "Something went wrong while inserting rows into the database \n" . $e->getMessage() . "\n";
            return false;
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
        }

        return true;

    }


}