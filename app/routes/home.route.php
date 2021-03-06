<?php
//die($GLOBALS['config']['home-url']);
// Get
$app->get('/', function ($request, $response) {
    // Check if already logged in
    if (!$_SESSION['user_id']) {
        // Render login if not logged in
        return $this->view->render($response, 'home/login.twig', [
            'homeURL' => $GLOBALS['config']['homeURL']
        ]);
    } else {
        // Redirect to panel
        return $response->withHeader("Location", "/panel");
    }
})->setName('home');

// Post 
$app->post('/', function ($request, $response) {
    // Define POST data
    $params = $request->getParams();

    // Verify required fields are not empty
    if (Utilities::checkAllFieldsNotEmpty($params)) {
        // Attempt Login
        if (User::attemptLogin($params)) {
            // Redirect after successful login
            return $response->withHeader('Location', '/panel');
        } else {
            // Return login view with error if one is empty
            return $this->view->render($response, 'home/login.twig', [
                'error' => "Incorrect username or password",
                'homeURL' => $GLOBALS['config']['homeURL']
            ]);
        }
    } else {
        // Return login view with error if one is empty
        return $this->view->render($response, 'home/login.twig', [
            'error' => "Please fill in all fields",
            'username' => $params['username'],
            'homeURL' => $GLOBALS['config']['homeURL']
        ]);
    }
});