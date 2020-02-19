<?php

namespace CatalystTask;
/**
 * Create table in PostgresSQL from PHP demo
 */
class CreateTable {

    /**
     * PDO object
     * @var \PDO
     */
    private $pdo;

    /**
     * init the object with a \PDO object
     * @param type $pdo
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * create tables
     */
    public function createTable() {
        $sql = 'CREATE TABLE IF NOT EXISTS users (
                        name character varying(255),
                        surname character varying(255), 
                        email  character varying(255) NOT NULL UNIQUE PRIMARY KEY
                     )';


        // execute sql statement to create new table
        $this->pdo->exec($sql);

        return $this;
    }


}