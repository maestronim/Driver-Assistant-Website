<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/user_info.php';
	
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
	
    // instantiate object
    $user_info = new UserInfo($db);

    // get posted data
    $data = json_decode(file_get_contents("php://input"));

    // set user property values
    $user_info->username = $data->username;
    $user_info->password = password_hash($data->password, PASSWORD_DEFAULT);
    $user_info->email = $data->email;

    // create the user
    if($user_info->create()){
        echo '{';
        	echo '"success": "yes",';
            echo '"message": "User was created."';
        echo '}';
    }

    // if unable to create the user
    else{
        echo '{';
        	echo '"success": "no",';
            echo '"message": "Unable to create user."';
        echo '}';
    }
?>