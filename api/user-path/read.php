<?php
	// required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once '../config/database.php';
    include_once '../objects/user_path.php';

    // get database connection
    $database = new Database();
    $db = $database->getConnection();

    // instantiate object
    $user_path = new UserPath($db);
    
    // get posted data
    $data = json_decode(file_get_contents("php://input"));

    $user_path->user_id = $data->user_id;
    $user_path->path_date = $data->path_date;

    $stmt = $user_path->read();

    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
        $paths = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $json_data = file_get_contents($row['url']);
            $data = json_decode($json_data);

            $path = array(
                "hard_braking" => $row['hard_braking'],
                "speed_limit_exceeded" => $row['speed_limit_exceeded'],
                "dangerous_time" => $row['dangerous_time'],
                "duration" => $row['duration'],
                "coordinates" => $data->coordinates
            );

            array_push($paths, $path);
      }

      $response = array(
          "success" => "yes",
          "paths" => $paths
      );

      $response_json = json_encode($response);

      echo $response_json;
    } else {
        echo '{';
        echo '"success": "no",';
        echo '"message": "No paths found"';
        echo '}';
    }
?>