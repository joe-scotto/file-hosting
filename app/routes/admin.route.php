<?php

// Grab user from database
$user = User::returnUserInfo($_SESSION['user_id']);

// Get
$app->get('/admin', function ($request, $response) use ($user) {
    // Verify user is an admin and logged in
    if ($_SESSION['user_id'] && $user['admin'] == 1) {
        // Return admin panel view
        return $this->view->render($response, 'admin/admin.twig', [
            'name' => $user['name']
        ]);
    } else {
        // Redirect to login
        return $response->withHeader("Location", "/");
    }
});

// Post 
$app->post('/admin', function ($request, $response) {
    // Define POST data
    $params = $request->getParams();

    // Check if add user was attempted
    if ($params['submit']) {
        // Verify all fields are filled in
        if (Utilities::checkAllFieldsNotEmpty($params)) {
            // Make sure username is not taken
            if (Admin::checkUsername($params['username'])) {
                // Check if directory is created
                if (Admin::createUsersDirectory($params['username'])) {
                    // Attempt to create user
                    if (Admin::createNewUser($params['name'], $params['username'], $params['password'], $params['admin'])) {
                        Utilities::setMessage("Excellent!", "User was created successfully.", "admin_modal");
                    } else {
                        // Return error if user is not created
                        Utilities::setMessage("Whoa!", "User could not be created due to an unknown error.", "admin_modal");
                    }
                } else {
                    // Return error if directory could not be created
                    Utilities::setMessage("Whoa!", "Could not create user because the directory already exists.", "admin_modal");
                }
            } else {
                // Return error if username is in use
                Utilities::setMessage("Whoa!", "Username in use.", "admin_modal");
            }
        } else {
            // Return error if all fields are not filled in
            Utilities::setMessage("Whoa!", "Please fill in all fields", "admin_modal");
        }

        // Return admin view with message
        return $this->view->render($response, 'admin/admin.twig', [
            'name' => $user['name'],
            'message' => $_SESSION['message'],
            'form' => [
                'name' => $params['name'],
                'username' => $params['username'],
                'admin' => $params['admin']
            ]
        ]);
    }
});