<?php
	// required headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header('Content-Type: application/json');
    
    include_once '../config/database.php';
    include_once '../objects/user_path.php';
    include_once '../objects/car_parameters.php';

    // get database connection
    $database = new Database();
    $db = $database->getConnection();

    // instantiate object
    $user_path = new UserPath($db);
    $car_parameters = new CarParameters($db);
	
	// set car parameters property values
    $user_path->user_id = isset($_GET['user_id']) ? $_GET['user_id'] : die();
    $user_path->path_date = isset($_GET['path_date']) ? $_GET['path_date'] : die();;

    $stmt_1 = $user_path->getIDs();
    $num_1 = $stmt_1->rowCount();

    // check if more than 0 record found
    if($num_1>0){
    	$parameters_path = [];
    	while ($row_1 = $stmt_1->fetch(PDO::FETCH_ASSOC)){
            $car_parameters->path_id = $row_1['id'];
            $stmt_2 = $car_parameters->read();
            $num_2 = $stmt_2->rowCount();
            if($num_2 > 0) {
                $parameters_list= [];
                while ($row_2 = $stmt_2->fetch(PDO::FETCH_ASSOC)){
                    $parameters = array(
                        $row_2['oilTemperature'],
                        $row_2['RPM'],
                        $row_2['throttlePosition'],
                        $row_2['airFuelRatio']
                    );

                    array_push($parameters_list, $parameters);
                }
           	} else {
                    echo '{';
                    echo '"success": "no",';
                    echo '"message": "No parameters found"';
                    echo '}';
           	}
            
            array_push($parameters_path, $parameters_list);
    	}

        $response = array(
          "success" => "yes",
          "pathsParameters" => $parameters_path
        );

        $response_json = json_encode($response);

        echo $response_json;
    } else {
          echo '{';
          echo '"success": "no",';
          echo '"message": "No parameters found"';
          echo '}';
  	}
?>