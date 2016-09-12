<?php

class Admin {
    /**
     * Returns a database instance
     * @return resource Instance of database
     */
    public static function getDatabase () {
        return Database::getInstance()->getConnection();
    }

    /**
     * Verifies the supplied username is not taken
     * @param  string $username Username to check
     * @return bool Returns false if found, true if not.
     */
    public static function checkUsername ($username) {
        // Define Query
        $query = "SELECT id FROM `users` WHERE username = :username";

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
        $query = "INSERT INTO `users` (name, username, password, admin) VALUES(:name, :username, :password, :admin)";

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