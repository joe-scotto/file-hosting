<?php

class Panel {
    private $_basePath;

    /**
     * Sets all required function params
     * @param string $username Specified users username for directory
     */
    public function __construct ($username) {
        // Set base path
        $this->_basePath = "../users/" . $username . '/';
    }

    /**
     * Formats breadcrumbs into directories
     * @param  array $breadcrumbs Path from URL
     * @return array Formatted breadcrumb directories
     */
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

    private function checkIfDirectory ($file) {
        
    }

    public function listDirectory ($directory = null) {   
        // Scan directory
        $files = scandir($this->_basePath . $directory);

        // Excluded files array
        $excluded = array(
            '.',
            '..'
        );

        // Return files
        return array_values(array_diff($files, $excluded));
    }

    /**
     * Creates a new directory
     * @param string $name Name of the new folder
     * @param string $path Where to create the folder
     * @return bool 
     */
    public function createNewDirectory ($name, $path = null) {
        // Check if path is root or subdirectory
        if (!$path) {
            // Define Path
            $path = $this->_basePath . $name;

            // Verify path does not exist
            if (!is_dir($path)) {
                // Create Directory
                mkdir($path);

                // Return True
                return true;
            } else {
                // Return False
                return false;
            }
        } else {
            // Define Path
            $path = $this->_basePath . str_replace('/panel', '', $path) . '/' . $name;

            // Verify path does not exist
            if (!is_dir($path)) {
                // Create Directory
                mkdir($path);

                // Return True
                return true;
            } else {
                // Return False
                return false;
            }
        }
    }
}