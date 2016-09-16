<?php

class Utilities {
    /**
     * Sets a message to be displayed
     * @param string $title Message title
     * @param string $message Message message
     */
    public static function setMessage ($title, $message, $modal) {
        // Set Message
        $_SESSION['message'] = array(
            'title' => $title,
            'message' => $message,
            'modal' => $modal
        );

        // Set Cookie        
        setcookie("sessionPersist", 1);

        // Return true
        return true;
    }

    /**
     * Removes a message after page is refreshed once
     * @return void
     */
    public static function removeMessage () { 
        if (isset($_COOKIE['sessionPersist'])) {
            if ($_COOKIE['sessionPersist'] == 1) {
                // Prepare cookie to unset on next reload
                setcookie('sessionPersist', $_COOKIE['sessionPersist'] + 1);
            } else if ($_COOKIE['sessionPersist'] == 2) {
                // Unset Message
                unset($_SESSION['message']);

                // Unset session persistence cookie
                setcookie('sessionPersist', 0);
            } else if ($_COOKIE['sessionPersist'] == 0) {
                // Unset session persistence cookie
                setcookie('sessionPersist', "", time()-1);
            }
        } else if (!isset($_COOKIE['sessionPersist']) || $_COOKIE['sessionPersist'] == 0) {
            // Unset Message
            unset($_SESSION['message']);

            // Unset session persistence cookie
            unset($_COOKIE['sessionPersist']);
        }
    }

    /**
     * Verifies all supplied fields contain data
     * @param  mixed $fields Array of fields and their values
     * @return bool True if all are filled in, false if not
     */
    public static function checkAllFieldsNotEmpty ($fields) {
        // Loop through each field and fail if one is empty
        foreach ($fields as $field) {
            if (!$field) {
                return false;
            }
        }

        // Return true if all fields are filled in
        return true;
    }
}