<?php 

// Check if database exists 
$db = Database::getInstance()->getConnection();

// Define Query
$query = "SHOW TABLES";

// Prepare Query
$preparedQuery = $db->prepare($query);

// Execute Query
if ($preparedQuery->execute($queryParams)) {
    // Define Results
    $results = $preparedQuery->fetch(PDO::FETCH_ASSOC);

    if (!in_array($GLOBALS['config']['database']['table'], $results)) {
        // Define create table query
        $createQuery = "CREATE TABLE " . $GLOBALS['config']['database']['table'] . " (
                    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                    `name` text NOT NULL,
                    `username` varchar(18) NOT NULL DEFAULT '',
                    `password` varchar(512) NOT NULL DEFAULT '',
                    `admin` int(11) DEFAULT '0',
                    PRIMARY KEY (`id`)
                 ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

        // Prepare Query
        $preparedCreateQuery = $db->prepare($createQuery);

        // Create database
        $preparedCreateQuery->execute();
    }
} else {
    die("Error with database connection.");
}

// First check if user exists and data is being updated
$user = Admin::selectUser($admin['username']);

// Add new admin if not found, otherwise update
if (!Admin::selectUser($admin['username'])) {
    // Check that username is not in use
    if (Admin::checkUsername($admin['username'])) {
        // Attempt to add user
        if (Admin::createNewUser($admin['name'], $admin['username'], $admin['password'], $admin['admin'])) {
            // Attempt to create directory
            if (Admin::createUsersDirectory($admin['username'])) {
                Utilities::setMessage('Success!', 'Admin was created successfully.', 'message_modal');
            } else {
                // Set Error upon failure
                Utilities::setMessage('Admin Error', 'Unable to create users directory, check if it already exists or file permissions.', 'message_modal');  
            }
        } else {
            // Set Error upon failure
            Utilities::setMessage('Admin Error', 'Unknown error when creating user.', 'message_modal');    
        }
    } else {
        // Set Error upon failure
        Utilities::setMessage('Admin Error', 'Username is in use.', 'message_modal');
    }
} else if ($admin['name'] !== $user['name'] || $admin['admin'] !== $user['admin'] || !password_verify($admin['password'], $user['password'])){
    // Update admin credentials
    if (Admin::updateCredentials($admin['name'], $admin['username'], $admin['password'], $admin['admin'])) {
        // Output message upon success
        Utilities::setMessage('Success!', 'Admin was updated.', 'message_modal');
    } else {
        // Output message if update fails
        Utilities::setMessage('Admin Error', 'Unkown error when updating admin.', 'message_modal');
    }
} 