<?php

class User {
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
    private static function checkUsername ($username) {
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
     * Inserts a new user into the database
     * @param string $name Name of the user
     * @param string $username Username of the user
     * @param string $password Password of the user
     * @return bool True if 
     */
    private static function addUser ($name, $username, $password) {
        // Define Query
        $query = "INSERT INTO `users` (name, username, password) VALUES(:name, :username, :password)";

        // Define query parameters
        $queryParams = [
            ':name' => $name,
            ':username' => $username,
            ':password' => $password
        ];

        // Prepare Query
        $preparedQuery = self::getDatabase()->prepare($query);

        // Execute Query
        if ($preparedQuery->execute($queryParams)) {
            return true;
        } else {
            return false;
        }

        // Create users directory
        if (!is_dir("../users/" . $username)) {
            if (mkdir("../users/" . $username)) {
                return true;
            }
        } else {
            return false;
        }

        // Return true if all conditions are passed
        return true;
    }

    /**
     * Selects a user from the database
     * @param  string $username User name of the user
     * @return mixed Array of database columns for specified username
     */
    private static function selectUserFromDatabase ($username) {
        // Define Query 
        $query = "SELECT * FROM `users` WHERE username = :username";

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

        // Return query results
        return $preparedQuery->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Creates a new user
     * @param string $name Name of the user
     * @param string $username Username of the user
     * @param string $password Password of the user
     * @return bool True if successful, false if not.
     */
    public static function createNewUser($name, $username, $password) {
        // Database Connection
        $db = Database::getInstance()->getConnection();

        // Format Variables
        $name = ucwords(trim($name));
        $username = strtolower(trim($username));
        $password = password_hash($password, PASSWORD_BCRYPT);

        // Add user is username is not taken
        if (self::checkUsername($username)) {
            if (self::addUser($name, $username, $password)) {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * Verifies all supplied fields contain data
     * @param  mixed $fields Array of fields and their values
     * @return bool True if all are filled in, false if not
     */
    public static function checkAllFieldsNotEmpty ($fields) {
        // Loop through each field and fail if one is empty
        foreach ($fields as $field) {
            if (!$field) {
                return false;
            }
        }

        // Return true if all fields are filled in
        return true;
    }

    /**
     * Attempts to log a user in
     * @param mixed $params Array containing POST data
     * @return bool True if logged in, false if not
     */
    public static function attemptLogin ($params) {
        // Format Parameters
        $username = strtolower(trim($params['username']));
        $password = $params['password'];

        // Select user from database
        $user = self::selectUserFromDatabase($username);

        // Verify password is correct
        if (password_verify($password, $user['password'])) {
            // Set session and return true
            $_SESSION['user_id'] = $user['id'];
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns information about a user
     * @param int $id Users ID
     * @return mixed Array of columns for the specified user id
     */
    public static function returnUserInfo ($id) {
        // Define Query
        $query = "SELECT * FROM `users` WHERE id = :id";

        // Prepare Query
        $preparedQuery = self::getDatabase()->prepare($query);

        // Define query parameters
        $queryParams = [
            ':id' => $id
        ];

        // Execute Query
        if ($preparedQuery->execute($queryParams)) {
            return $preparedQuery->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    /**
     * Logs out the user
     * @return void
     */
    public static function logout () {
        // Unset users id session
        unset($_SESSION['user_id']);
    }
}