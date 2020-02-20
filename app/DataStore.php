<?php
/**
 * Created by PhpStorm.
 * User: jacinto
 * Date: 20/2/20
 * Time: 6:54 PM
 */

namespace CatalystTask;

/**
 * Create table in PostgresSQL from PHP demo
 */
class DataStore {


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
     * insert a new row into the stocks table
     * @param type $symbol
     * @param type $company
     * @return the id of the inserted row
     */
    public function insertRow($name, $surname, $email) {
        // prepare statement for insert
        $sql = 'INSERT INTO users(name,surname,email) VALUES(:name,:surname, :email)';
        $stmt = $this->pdo->prepare($sql);

        // pass values to the statement
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':surname', $surname);
        $stmt->bindValue(':email', $email);

        // execute the insert statement
        $stmt->execute();

    }

}