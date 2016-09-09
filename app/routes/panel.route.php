<?php

// Define variables
$user = User::returnUserInfo($_SESSION['user_id']);
$panel = new Panel($user['username']);

// Get 
$app->get('/panel', function ($request, $response) use ($panel, $user) {
    // Verify user is logged in
    if (!$_SESSION['user_id']) {
        // Redirect to login page if not logged in
        return $response->withHeader('Location', '/');
    } else {
        // Define files
        $files = $panel->listDirectory();

        // Return panel view with data
        return $this->view->render($response, 'panel/panel.twig', [
            'name' => $user['name'],
            'username' => $user['username'],
            'files' => $files,
            'url' => '/panel',
            'message' => $_SESSION['message']
        ]);
    }
});

$app->get('/panel/[{path:.*}]', function ($request, $response) use ($panel, $user) {
    // Verify user is logged in
    if (!$_SESSION['user_id']) {
        // Redirect to login page if not logged in
        return $response->withHeader('Location', '/');
    } else {
        // Breadcrumbs
        $breadcrumbs = explode('/', $request->getAttribute('path'));

        // Breadcrumb Paths
        $breadcrumbDirectory = Panel::formatBreadcrumbs($breadcrumbs);

        // Define files
        $files = $panel->listDirectory($request->getAttribute('path'));

        var_dump($files);

        echo $request->getUri()->getPath();

        // Return panel view with data
        return $this->view->render($response, 'panel/panel.twig', [
            'name' => $user['name'],
            'username' => $user['username'],
            'breadcrumbs' => array(
                'path' => $breadcrumbs,
                'directory' => $breadcrumbDirectory
            ),
            'files' => $files,
            'url' => $request->getUri()->getPath(),
            'message' => $_SESSION['message']
        ]);
    }
});

// Post
$app->post('/panel', function ($request, $response) use ($panel) {
    // Define POST data
    $params = $request->getParams();

    // Logout User
    if ($params['logout']) {
        // Logout
        User::logout();
    }

    if (isset($params['submit_folder'])) {
        // Check to see if new folder was created, redirect to original page if not.
        if ($panel->createNewDirectory($params['folder_name'])) {
            // Redirect to new folder
            return $response->withHeader('Location', '/panel/' . $params['folder_name']);
        } else {
            // Set error message 
            Utilities::setMessage("Whoa!", "That directory already exists, please choose another name.");

            // Redirect to original page
            return $response->withHeader('Location', '/panel');
        }
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

    if (isset($params['submit_folder'])) {
        // Check to see if new folder was created, redirect to original page if not.
        if ($panel->createNewDirectory($params['folder_name'], $request->getUri()->getPath())) {
            // Redirect to new folder
            return $response->withHeader('Location', $request->getUri()->getPath() . '/' . $params['folder_name']);
        } else {
            // Set error message 
            Utilities::setMessage("Whoa!", "That directory already exists, please choose another name.");
            
            // Redirect to original page
            return $response->withHeader('Location', $request->getUri()->getPath());
        }
    }
});