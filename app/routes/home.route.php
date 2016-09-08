<?php

// Get
$app->get('/', function ($request, $response) {
    // Check if already logged in
    if (!$_SESSION['user_id']) {
        // Render login if not logged in
        return $this->view->render($response, 'home/login.twig');
    } else {
        // Redirect to panel
        return $response->withHeader("Location", "/panel");
    }
})->setName('home');

// Post 
$app->post('/', function ($request, $response) {
    // Define POST data
    $postData = $request->getParams();

    // Verify required fields are not empty
    if (User::checkAllFieldsNotEmpty($postData)) {
        // Attempt Login
        if (User::attemptLogin($postData)) {
            // Redirect after successful login
            return $response->withHeader('Location', '/panel');
        } else {
            // Return login view with error if one is empty
            return $this->view->render($response, 'home/login.twig', [
                'error' => "Incorrect username or password"
            ]);
        }
    } else {
        // Return login view with error if one is empty
        return $this->view->render($response, 'home/login.twig', [
            'error' => "Please fill in all fields"
        ]);
    }
});