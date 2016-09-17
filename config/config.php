<?php

// Global Config
$GLOBALS['config'] = [
    'database' => [
        'host' => '127.0.0.1',
        'database' => 'file-hosting',
        'username' => 'root',
        'password' => 'root'
    ],
];

// Create or update admin
$admin = [
    'name' => ucwords(trim("first last")),
    'username' => "username",
    'password' => "password",
    'admin' => "1"
];

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