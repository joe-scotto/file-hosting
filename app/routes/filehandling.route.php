<?php

// Define variables
$user = User::returnUserInfo($_SESSION['user_id']);

// Download file
$app->get('/file/[{file:.*}]', function ($request, $response, $args) use ($user) {
    // Check if logged in
    if (!$_SESSION['user_id']) {
        // Render login if not logged in
        return $response->withHeader('Location', '/');
    } else {    
        // Define file for download
        $file = "../users/" . $user['username'] . "/" . $args['file'];

        // Download File
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment;filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
    }
});

// Delete file or folder
$app->get('/delete/[{file:.*}]', function ($request, $response, $args) use ($user) {
    // Check if logged in
    if (!$_SESSION['user_id']) {
        // Render login if not logged in
        return $response->withHeader('Location', '/');
    } else {    
        // Define file or folder for deletion
        $file = "../users/" . $user['username'] . "/" . $args['file'];
        
        // Define post back url
        $url = $request->getQueryParams()['url'];

        // Define function to delete files
        function deleteFiles ($target) {
            if(is_dir($target)){
                $files = glob( $target . '*', GLOB_MARK );
                
                foreach( $files as $file )
                {
                    deleteFiles( $file );      
                }
              
                rmdir( $target );
            } elseif(is_file($target)) {
                unlink( $target );  
            }
        }

        // Delete files
        deleteFiles($file);

        // Return to previous url
        return $response->withHeader('Location', $url);
    }
});