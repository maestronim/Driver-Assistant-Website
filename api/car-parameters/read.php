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
$headers = apache_request_headers();
$authHeader = $headers['Authorization'];

if(validate_token($authHeader)) {

  // get database connection
  $database = new Database();
  $db = $database->getConnection();

  // instantiate object
  $car_parameters = new CarParameters($db);

  // set car parameters property values
  $car_parameters->path_id = isset($_GET['path_id']) ? $_GET['path_id'] : die();

  $stmt = $car_parameters->read();
  $num = $stmt->rowCount();
  if($num > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      foreach($car_parameters->get_parameters_list() as $parameter) {
        if($row[$parameter] != -1) {
          if(!isset($parameters[$parameter])) {
            $parameters[$parameter] = [];
          }
          array_push($parameters[$parameter], $row[$parameter]);
        }
      }
    }

    $parameters_list = [];
    foreach ($parameters as $k => $value) {
      $parameters_object['name'] = $k;
      $parameters_object['values'] = $value;
      array_push($parameters_list, $parameters_object);
    }

    $response = array(
      "success" => "yes",
      "parameters" => $parameters_list
    );

    echo json_encode($response);
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
