<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

require_once '../config/database.php';
require_once '../objects/user_path.php';
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
  $user_path = new UserPath($db);

  // get posted data
  $data = json_decode(file_get_contents("php://input"));

  // set path property values
  $user_path->user_id = isset($_GET['user_id']) ? $_GET['user_id'] : die();
  $user_path->path_date = isset($_GET['path_date']) ? $_GET['path_date'] : die();
  $user_path->offset = isset($_GET['offset']) ? $_GET['offset'] : die();

  $stmt = $user_path->read();

  $num = $stmt->rowCount();

  if($num>0){
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $json_data = file_get_contents($row['url']);
    $data = json_decode($json_data);

    $path = array(
      "id" => $row['id'],
      "hard_braking" => $row['hard_braking'],
      "speed_limit_exceeded" => $row['speed_limit_exceeded'],
      "dangerous_time" => $row['dangerous_time'],
      "duration" => $row['duration'],
      "coordinates" => $data->coordinates
    );

    $response = array(
      "success" => "yes",
      "path" => $path
    );

    $response_json = json_encode($response);

    echo $response_json;
  } else {
    echo '{';
      echo '"success": "no",';
      echo '"message": "No paths found"';
      echo '}';
    }
  } else {
    echo '{';
      echo '"success": "no",';
      echo '"message": "Access denied"';
      echo '}';
  }
  ?>
