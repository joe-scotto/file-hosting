<?php

class Admin {
    /**
     * Returns a database instance
     * @return resource Instance of database
     */
    public static function getDatabase () {
        return Database::getInstance()->getConnection();
    }

    public static function updateCredentials ($name, $username, $password, $admin) {
        // Format Variables
        $name = ucwords(trim($name));
        $username = strtolower(trim($username));
        $password = password_hash($password, PASSWORD_BCRYPT);

        // Check if admin
        if ($admin) {
            $admin = 1;
        } else {
            $admin = 0;
        }

        // Define Query 
        $query = "UPDATE " . $GLOBALS['config']['database']['table'] . " SET name = :name, password = :password, admin = :admin WHERE username = :username";

        // Prepare Query
        $preparedQuery = self::getDatabase()->prepare($query);

        // Define query parameters
        $queryParams = [
            ':name' => $name,
            ':username' => $username,
            ':password' => $password,
            ':admin' => $admin
        ];

        // Execute Query
        if (!$preparedQuery->execute($queryParams)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Selects a user from the database
     * @param  string $username User name of the user
     * @return mixed Array of database columns for specified username
     */
    public static function selectUser ($username) {
        // Define Query 
        $query = "SELECT * FROM " . $GLOBALS['config']['database']['table'] . " WHERE username = :username";

        // Prepare Query
        $preparedQuery = self::getDatabase()->prepare($query);

        // Define query parameters
        $queryParams = [
            ':username' => $username
        ];

        // Execute Query
        if (!$preparedQuery->execute($queryParams)) {
            return false;
        }

        // Return query results if found
        if ($preparedQuery->rowCount() >= 1) {
            return $preparedQuery->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    /**
     * Verifies the supplied username is not taken
     * @param  string $username Username to check
     * @return bool Returns false if found, true if not.
     */
    public static function checkUsername ($username) {
        // Define Query
        $query = "SELECT id FROM " . $GLOBALS['config']['database']['table'] . " WHERE username = :username";

        // Prepare Query
        $preparedQuery = self::getDatabase()->prepare($query);

        // Execute Query
        $preparedQuery->execute([
            ':username' => $username
        ]);

        // Check number of rows is not greater than 0
        if ($preparedQuery->rowCount() <= 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Creates a directory for a user
     * @param string $username Username of the user
     * @return bool
     */
    public static function createUsersDirectory ($username) {
        // Create users directory
        if (!is_dir("../users/" . $username)) {
            if (mkdir("../users/" . $username)) {
                // Modify directory permissions 
                chmod("../users/" . $username, 0744);

                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * Inserts a new user into the database
     * @param string $name Name of the user
     * @param string $username Username of the user
     * @param string $password Password of the user
     * @return bool True if 
     */
    private static function addUser ($name, $username, $password, $admin = null) {
        // Define Query
        $query = "INSERT INTO " . $GLOBALS['config']['database']['table'] . " (name, username, password, admin) VALUES(:name, :username, :password, :admin)";

        // Check if admin checkbox is set and define value
        if ($admin) {
            $admin = 1;
        } else {
            $admin = 0;
        }

        // Define query parameters
        $queryParams = [
            ':name' => $name,
            ':username' => $username,
            ':password' => $password,
            ':admin' => $admin
        ];

        // Prepare Query
        $preparedQuery = self::getDatabase()->prepare($query);

        // Execute Query
        if ($preparedQuery->execute($queryParams)) {
            return true;
        } else {
            return false;
        }

        // Return true if all conditions are passed
        return true;
    }

    /**
     * Creates a new user
     * @param string $name Name of the user
     * @param string $username Username of the user
     * @param string $password Password of the user
     * @return bool True if successful, false if not.
     */
    public static function createNewUser($name, $username, $password, $admin = null) {
        // Database Connection
        $db = Database::getInstance()->getConnection();

        // Format Variables
        $name = ucwords(trim($name));
        $username = strtolower(trim($username));
        $password = password_hash($password, PASSWORD_BCRYPT);

        // Add user 
        if (self::addUser($name, $username, $password, $admin)) {
            return true;
        } else {
           return false;
        }
    }
}