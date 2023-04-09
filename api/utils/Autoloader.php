<?php

    // class autoloader function from controllers folder or utils folder
    function autoloader($class) {
        // the auto loader link path should be from the perspective of the file that needs the autoloader 
        // check if class exists
        if (file_exists('../controllers/' . $class . '.php')) {
            // if class exists
            require_once '../controllers/' . $class . '.php';
        } else if (file_exists('../models/' . $class . '.php')) {
            // if class exists
            require_once '../models/' . $class . '.php';
        } else if (file_exists('../utils/' . $class . '.php')) {
            // if class exists
            require_once '../utils/' . $class . '.php';
        }
    }

    spl_autoload_register('autoloader');

?>