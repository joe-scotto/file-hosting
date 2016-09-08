<?php

class Panel {
    private $_basePath;
    private $_currentPath; 

    public function __construct ($username) {
        // Set base path
        $this->_basePath = "../users/" . $username . '/';
    }

    public static function formatBreadcrumbs ($breadcrumbs) {
        // Counter for array
        $counter = 0;

        // Loop through and append to previous if not first
        foreach ($breadcrumbs as $breadcrumb) {
            if ($counter == 0) {
                $currentPath = $breadcrumb;
            } else {
                $currentPath .= '/' . $breadcrumb;
            }

            $formattedBreadcrumbs[] = $currentPath;
            $counter++;
        }

        // Return formatted breadcrumbs
        return $formattedBreadcrumbs;
    }
}