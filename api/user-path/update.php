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

  // set path property values
  $user_path->user_id = $data->user_id;
  $user_path->path_date = $data->path_date;
  $user_path->hard_braking = $data->hard_braking;
  $user_path->speed_limit_exceeded = $data->speed_limit_exceeded;
  $user_path->dangerous_time = $data->dangerous_time;
  $user_path->duration = $data->duration;

  $db->beginTransaction();

  $stmt = $user_path->getUrl();

  if($stmt->rowCount() > 0) {
    $row = $stmt->fetch();
    $url = $row['url'];
    if($user_path->update()) {
      if(file_exists($url)) {
        $inp = file_get_contents($url);
        $tempArray = json_decode($inp);
        for ($i = 0; $i < count($data->coordinates); $i++) {
          array_push($tempArray->coordinates, $data->coordinates[$i]);
        }
        $jsonData = json_encode($tempArray);

        if(file_put_contents($url, $jsonData) !== false) {
          $db->commit();
          echo '{';
            echo '"success": "yes",';
            echo '"message": "Path was updated"';
            echo '}';
          } else {
            $db->rollback();
            echo '{';
              echo '"success": "no",';
              echo '"message": "Unable to update the path"';
              echo '}';
            }
          } else {
            $db->rollback();
            echo '{';
              echo '"success": "no",';
              echo '"message": "Unable to update the path"';
              echo '}';
            }
          } else {
            echo '{';
              echo '"success": "no",';
              echo '"message": "Unable to update the path"';
              echo '}';
            }
          } else {
            echo '{';
              echo '"success": "no",';
              echo '"message": "Unable to update the path"';
              echo '}';
            }
          } else {
            echo '{';
              echo '"success": "no",';
              echo '"message": "Access denied"';
              echo '}';
            }
            ?>
