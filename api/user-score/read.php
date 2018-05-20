<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: access");
	header("Access-Control-Allow-Methods: GET");
	header("Access-Control-Allow-Credentials: true");
	header('Content-Type: application/json');

    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/user_score.php';

    // instantiate database
    $database = new Database();
    $db = $database->getConnection();

    // initialize object
    $user_score = new UserScore($db);
    
    // set user_id property of score
	$user_score->user_id = isset($_GET['user_id']) ? $_GET['user_id'] : die();

    // get global score
    $stmt = $user_score->read();
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
		// retrieve our table contents
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
        // score array
		$score_array=array(
        	"hard_braking" => $row['hard_braking'],
        	"speed_limit_exceeded" => $row['speed_limit_exceeded'],
        	"dangerous_time" => $row['dangerous_time']
      	);
        
        $response_array=array(
        	"success" => "yes",
            "message" => "Score found",
            "data" => $score_array
        );

		// make it json format
        echo json_encode($response_array);
    } else{
        echo json_encode(
            array(
            "success" => "no",
            "message" => "Score not found")
        );
    }
?>