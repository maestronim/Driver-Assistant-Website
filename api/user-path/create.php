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

  $db->beginTransaction();

  // set path property values
  $user_path->user_id = $data->user_id;
  $user_path->path_date = $data->path_date;
  $user_path->url = "../../paths/" . $user_path->user_id . " " . str_replace(":", "-", $user_path->path_date) . ".json";

  if($user_path->create()) {
    $object = array(
      "type" => "linestring",
      "coordinates" => array()
    );

    $json_object = json_encode($object);

    if(file_put_contents($user_path->url, $json_object) !== false) {
      $db->commit();
      echo '{';
        echo '"success": "yes",';
        echo '"message": "Path was created"';
        echo '}';
      } else {
        $db->rollback();
        echo '{';
          echo '"success": "no",';
          echo '"message": "Unable to create the path"';
          echo '}';
        }
      } else {
        echo '{';
          echo '"success": "no",';
          echo '"message": "Unable to create the path"';
          echo '}';
        }
      } else {
        echo '{';
          echo '"success": "no",';
          echo '"message": "Access denied"';
          echo '}';
        }
        ?>
