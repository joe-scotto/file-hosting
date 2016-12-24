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

    /**
     * Lists files and folders in a directory
     * @param string $directory Where to start looking after the basepath
     * @return array Contains all files and folders found in a specified directory
     */
    public function listDirectory ($directory = null) {   
        // Scan Directory
        $files = scandir($this->_basePath . $directory);

        // Excluded files array (Case-Sensitive)
        $excluded = array(
            '.',
            '..'
        );

        // Remove excluded files from $files
        $files = array_diff($files, $excluded);

        // Reindex Array
        $files = array_values($files);

        // Return Files
        return $files;
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
                if (mkdir($path)) {
                    // Modify directory permissions 
                    chmod($path, 0744);
                    
                    // Return True
                    return true;
                } else {
                    return false;
                }
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
                if (mkdir($path)) {
                    // Modify directory permissions 
                    chmod($path, 0744);
                    
                    // Return True
                    return true;
                } else {
                    return false;
                }
            } else {
                // Return False
                return false;
            }
        }
    }

    public function checkFileSize ($files, $path = null) {
        // Check if path is root or subdirectory
        if (!$path) {
            // Define Path
            $path = $this->_basePath;

            // Define maximum file size 
            $maxFilesize = str_replace("M","", ini_get("upload_max_filesize")) * pow(1024, 2);

            // Upload multiple if more than one
            if (count($files) > 1) {
                foreach ($files as $file) {
                    if ($file->getSize() <= $maxFilesize) {
                        return true;
                    }
                }
            } else {
                // Grab first index
                $file = $files[0];

                if ($file->getSize() <= $maxFilesize) {
                    return true;
                }
            }
        } else {
            // Define Path
            $path = $this->_basePath . str_replace('/panel/', '', $path) . '/' . $name;

            // Upload multiple if more than one
            if (count($files) > 1) {
                foreach ($files as $file) {
                    if ($file->getSize() <= $maxFilesize) {
                        return true;
                    }
                }
            } else {
                // Grab first index
                $file = $files[0];

                if ($file->getSize() <= $maxFilesize) {
                    return true;
                }
            }
        }

        // Return true if no other errors
        return false;
    }

    /**
     * Uploads a single file or multiple depending on length of array
     * @param mixed $files Array containing information about the files
     * @param string $path Where to upload the file
     * @return void
     */
    public function uploadFiles ($files, $path = null) {
        // Check if path is root or subdirectory
        if (!$path) {
            // Define Path
            $path = $this->_basePath;

            // Upload multiple if more than one
            if (count($files) > 1) {
                foreach ($files as $file) {
                    if ($file->getError() === UPLOAD_ERR_OK) {
                        $uploadFileName = $file->getClientFilename();
                        $file->moveTo($path . $uploadFileName);
                    }
                }
            } else {
                // Grab first index
                $files = $files[0];

                // Upload File
                if ($files->getError() === UPLOAD_ERR_OK) {
                    $uploadFileName = $files->getClientFilename();
                    $files->moveTo($path . $uploadFileName);
                }
            }
        } else {
            // Define Path
            $path = $this->_basePath . str_replace('/panel/', '', $path) . '/' . $name;

            // Upload multiple if more than one
            if (count($files) > 1) {
                foreach ($files as $file) {
                    if ($file->getError() === UPLOAD_ERR_OK) {
                        $uploadFileName = $file->getClientFilename();
                        $file->moveTo($path . $uploadFileName);
                    }
                }
            } else {
                // Grab first index
                $files = $files[0];

                // Upload file
                if ($files->getError() === UPLOAD_ERR_OK) {
                    $uploadFileName = $files->getClientFilename();
                    $files->moveTo($path . $uploadFileName);
                }
            }
        }
    }

    /**
     * Checks if a path is a directory
     * @param mixed $files Array containing information about the paths
     * @param string $path Where to look
     * @return array If folder, add 1. If file, add 0
     */
    public function checkIfDirectory ($files, $path = null) {
        // Define return array
        $directories = array();

        // Check if path is home or not
        if (!$path) {
            $path = $this->_basePath;
        } else {
            $path = $path = $this->_basePath . str_replace('/panel/', '', $path) . '/';
        }

        // Loop through each file and check if is directory
        foreach ($files as $file) {
            if (is_dir($path . $file)) {
                // Add 1 if directory
                $directories[] = 1;
            } else {
                // Add 0 if file
                $directories[] = 0;
            }
        }

        // Return directories array
        return $directories;
    }

    /**
     * Counts files in a given path
     * @param mixed $files Array containing information about the paths
     * @param string $path Where to look
     * @return array Count if directory, null if not
     */
    public function countFilesInDirectory ($files, $path = null) {
        // Define return array
        $count = array();

        // Check if path is home or not
        if (!$path) {
            $path = $this->_basePath;
        } else {
            $path = $path = $this->_basePath . str_replace('/panel/', '', $path) . '/';
        }
    
        // Loop through each file and count files
        foreach ($files as $file) {
            if (is_dir($path . $file)) {
                // Scan Path
                $files = scandir($path . $file);

                // Count number of files in directory minus parent / child
                $number = count($files) - 2;

                // Add to count array
                $count[] = $number;
            } else {
                // Add null value if file
                $count[] = null;
            }
        }

        // Return count array
        return $count;
    }

    /**
     * Verifies the folder name fits specific parameters
     * @param string $name Name of the folder
     * @return bool False if failure, true if success
     */
    public function checkFolderName ($name) {
        // Verify string does not start with a dot 
        if (substr($name, 0, 1) === ".") {
            return false;
        }

        // Check that string only contains alphanumeric characters and dash, dot, and underscore
        if (!preg_match('/^[a-z0-9 .\-\_]+$/i', $name)) {
            return false;
        }

        // Check that string is not just dots and spaces
        if (preg_match('/^[ \ .]+$/i', $name)) {
            return false;
        }

        // Return true if all cases pass
        return true;
    }
}