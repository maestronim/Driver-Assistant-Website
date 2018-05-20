<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/user_path.php';
    include_once '../objects/car_parameters.php';
	
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
	
    // instantiate object
    $car_parameters = new CarParameters($db);
    $user_path = new UserPath($db);

    // get posted data
    $data = json_decode(file_get_contents("php://input"));

    // set user property values
    $car_parameters->oilTemperature = $data->oilTemperature;
    $car_parameters->RPM = $data->RPM;
    $car_parameters->throttlePosition = $data->throttlePosition;
    $car_parameters->airFuelRatio = $data->airFuelRatio;
    
    $user_path->user_id = $data->user_id;
    $user_path->path_date = $data->path_date;
    
    $stmt = $user_path->getID();
    
    if($stmt->rowCount() > 0) {
    	$row = $stmt->fetch();
        $car_parameters->path_id = $row['id'];
        // create the parameters
        if($car_parameters->create()){
            echo '{';
                echo '"success": "yes",';
                echo '"message": "Parameters were created."';
            echo '}';
        }

        // if unable to create parameters
        else{
            echo '{';
                echo '"success": "no",';
                echo '"message": "Unable to create parameters."';
            echo '}';
        }
	} else{
          echo '{';
          echo '"success": "no",';
          echo '"message": "Unable to create parameters."';
          echo '}';
	}
?>