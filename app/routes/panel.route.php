<?php

// Object Instances
$panel = new Panel($user['username']);

// Get 
$app->get('/panel', function ($request, $response) use ($panel) {
    // Verify user is logged in
    if (!$_SESSION['user_id']) {
        // Redirect to login page if not logged in
        return $response->withHeader('Location', '/');
    } else {
        // Get user data
        $user = User::returnUserInfo($_SESSION['user_id']);

        // Return panel view with data
        return $this->view->render($response, 'panel/panel.twig', [
            'name' => $user['name'],
            'username' => $user['username']
        ]);
    }
});

$app->get('/panel/[{path:.*}]', function ($request, $response) use ($panel) {
    // Verify user is logged in
    if (!$_SESSION['user_id']) {
        // Redirect to login page if not logged in
        return $response->withHeader('Location', '/');
    } else {
        // Get user data
        $user = User::returnUserInfo($_SESSION['user_id']);
        
        // Breadcrumbs
        $breadcrumbs = explode('/', $request->getAttribute('path'));

        // Breadcrumb Paths
        $breadcrumbDirectory = Panel::formatBreadcrumbs($breadcrumbs);

        // Return panel view with data
        return $this->view->render($response, 'panel/panel.twig', [
            'name' => $user['name'],
            'username' => $user['username'],
            'breadcrumbs' => array(
                'path' => $breadcrumbs,
                'directory' => $breadcrumbDirectory
            )
        ]);
    }
});

// Post
$app->post('/panel', function ($request, $response) {
    // Define POST data
    $params = $request->getParams();

    // Logout User
    if ($params['logout']) {
        // Logout
        User::logout();
    }
});

$app->post('/panel/[{path:.*}]', function ($request, $response) use ($panel) {
    // Define POST data
    $params = $request->getParams();

    // Logout User
    if ($params['logout']) {
        // Logout
        User::logout();
    }
});