<?php

// Grab user from database
$user = User::returnUserInfo($_SESSION['user_id']);

// Get
$app->get('/admin', function ($request, $response) use ($user) {
    // Verify user is an admin and logged in
    if ($_SESSION['user_id'] && $user['is_admin'] == 1) {
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
    
    // Logout User
    if ($params['logout']) {
        // Logout
        User::logout();

        // Redirect to login page
        return $response->withHeader("Location", "/");
    }
});