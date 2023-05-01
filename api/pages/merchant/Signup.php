<?php

    // enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");

    // include autoloader.php file for the classes
    require_once '../../utils/autoloader.php';

    $user_data = json_decode(file_get_contents("php://input"));

    $merchantController = new MerchantController();

    $response = $merchantController->signupMerchant($user_data->fullname, $user_data->username, $user_data->phone_number, $user_data->email_address, $user_data->account_type, $user_data->password, $user_data->retype_password);

    // send response
    echo json_encode($response);
?>