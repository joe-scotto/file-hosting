<?php

class Database {
    // Class Properties
    private static $_instance;
    public $pdo;

    public function getConnectionProperties ($property) {
        return $GLOBALS['config']['database'][$property];
    }

    /**
     * Singleton for database connection
     * @return class Returns one instance of the Database class
     */
    public static function getInstance() {
        if(!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Connects to database
     * @return mysql PDO connection to mysql databse
     */
    public function getConnection() {
        try {
            //Attempt to connect
            $this->pdo = new PDO('mysql:host=' . $this->getConnectionProperties('host') . ';dbname=' . $this->getConnectionProperties('database') , $this->getConnectionProperties('username'), $this->getConnectionProperties('password'));

            //Return connection
            return $this->pdo;
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }
}

