<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../config/database.php';
include_once '../objects/car_parameters.php';
require_once '../token/validate.php';

/*
 * Get all headers from the HTTP request
 */
$headers    = apache_request_headers();
$authHeader = $headers['Authorization'];

if (validate_token($authHeader, isset($_GET['user_id']) ? $_GET['user_id'] : die())) {
    // get database connection
    $database = new Database();
    $db       = $database->getConnection();

    // instantiate object
    $car_parameters = new CarParameters($db);

    // set car parameters property values
    $car_parameters->path_id = isset($_GET['path_id']) ? $_GET['path_id'] : die();

    $stmt = $car_parameters->read();
    $num  = $stmt->rowCount();
    $parameters_count = 0;
    if ($num > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        foreach ($car_parameters->get_parameters_list() as $parameter) {
            if ($row[$parameter] != -1) {
                $parameters_count++;
            }
        }

        if($parameters_count > 0) {
          $response = array(
              "success" => "yes",
              "count" => $parameters_count
          );

          $response_json = json_encode($response);

          echo $response_json;
        } else {
            echo '{';
            echo '"success": "no",';
            echo '"message": "No parameters found"';
            echo '}';
        }
    } else {
        echo '{';
        echo '"success": "no",';
        echo '"message": "No parameters found"';
        echo '}';
    }
} else {
    echo '{';
    echo '"success": "no",';
    echo '"message": "Access denied"';
    echo '}';
}
?>
