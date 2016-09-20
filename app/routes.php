<?php

// Middleware to remove trailing slash
$app->add(function ($request, $response, $next) {
    $uri = $request->getUri();
    $path = $uri->getPath();
    if ($path != '/' && substr($path, -1) == '/') {
        // permanently redirect paths with a trailing slash
        // to their non-trailing counterpart
        $uri = $uri->withPath(substr($path, 0, -1));
        return $response->withRedirect((string)$uri, 301);
    }

    // Return next middleware
    return $next($request, $response);
});

// Middleware to logout
$app->add(function ($request, $response, $next) {
    // Define POST data
    $params = $request->getParams();
    
    // Logout User
    if ($params['logout']) {
        // Logout
        User::logout();

        // Redirect to login page
        return $response->withHeader("Location", "/");
    }

    // Return next middleware
    return $next($request, $response);
});

// Homepage 
include 'routes/home.route.php';

// Panel
include 'routes/panel.route.php';

// Admin
include 'routes/admin.route.php';

// File
include 'routes/filehandling.route.php';