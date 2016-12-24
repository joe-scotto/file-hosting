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
     * Selects a user from the database
     * @param  string $username User name of the user
     * @return mixed Array of database columns for specified username
     */
    private static function selectUserFromDatabase ($username) {
        // Define Query 
        $query = "SELECT * FROM `" . $GLOBALS['config']['database']['table'] . "` WHERE username = :username";

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
     * Returns information about a user
     * @param int $id Users ID
     * @return mixed Array of columns for the specified user id
     */
    public static function returnUserInfo ($id) {
        // Define Query
        $query = "SELECT * FROM `" . $GLOBALS['config']['database']['table'] . "` WHERE id = :id";

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