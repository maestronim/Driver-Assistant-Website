<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../config/database.php';
include_once '../objects/car_parameters.php';

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
  $parameters_list= [];
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $parameters = array(
      $row['oilTemperature'],
      $row['RPM'],
      $row['throttlePosition'],
      $row['airFuelRatio']
    );

    array_push($parameters_list, $parameters);
  }

  $response = array(
    "success" => "yes",
    "parameters" => $parameters_list
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
