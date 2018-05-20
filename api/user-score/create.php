<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/user_score.php';
	
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
	
    // instantiate object
    $user_score = new UserScore($db);

    // get posted data
    $data = json_decode(file_get_contents("php://input"));

    // set user property values
    $user_score->hard_braking = $data->hard_braking;
    $user_score->speed_limit_exceeded = $data->speed_limit_exceeded;
    $user_score->dangerous_time = $data->dangerous_time;
    $user_score->user_id = $data->user_id;

    // create the user
    if($user_score->create()){
        echo '{';
        	echo '"success": "yes",';
            echo '"message": "Score was created"';
        echo '}';
    }

    // if unable to create the user
    else{
        echo '{';
        	echo '"success": "no",';
            echo '"message": "Unable to create score"';
        echo '}';
    }
?>