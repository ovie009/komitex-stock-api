<?php

    // enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");

    // include autoloader.php file for the classes
    require_once '../../utils/autoloader.php';

    $payload = json_decode(file_get_contents("php://input"));

    $logisticsController = new LogisticsController();

    switch ($payload->action) {
        case 'locationTableNotEmpty':
            $response = $logisticsController->locationTableNotEmpty($payload->company_id);
            break;
        
        case 'addLocation':
            $response = $logisticsController->addLocation($payload->company_id, $payload->location, $payload->charge, $payload->fullname, $payload->user_id);
            break;
        
        default:
            # code...
            break;
    }


    // send response
    echo json_encode($response);

    // echo 'got here';
?>