<?php

    // enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");

    // include autoloader.php file for the classes
    require_once '../../utils/autoloader.php';

    $user_data = json_decode(file_get_contents("php://input"));

    $generalController = new GeneralController();

    $response = $generalController->login( $user_data->email_address, $user_data->password);

    // send response
    echo json_encode($response);
?>