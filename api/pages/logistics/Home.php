<?php

    // enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");

    // include autoloader.php file for the classes
    require_once '../../utils/autoloader.php';

    $package = json_decode(file_get_contents("php://input"));

    $logisticsView = new LogisticsView();

    switch ($package->action) {
        case 'checkForInventory':
            # code...
            // check for inventory
            $response = $logisticsView->checkInventory($package->company_id);
            break;
        
        default:
            # code...
            break;
    }

    echo json_encode($response);
?>