<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../config/database.php';
include_once '../objects/user_path.php';
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
    $user_path = new UserPath($db);

    // get posted data
    $data = json_decode(file_get_contents("php://input"));

    // set path property values
    $user_path->user_id   = isset($_GET['user_id']) ? $_GET['user_id'] : die();
    $user_path->path_date = isset($_GET['path_date']) ? $_GET['path_date'] : die();

    $stmt = $user_path->count();

    $num = $stmt->rowCount();

    if ($num > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $response = array(
            "count" => $row['paths_count']
        );

        $response_json = json_encode($response);

        echo $response_json;
    } else {
        echo '{';
        echo '"count": "0"';
        echo '}';
    }
} else {
    echo '{';
    echo '"success": "no",';
    echo '"message": "Access denied"';
    echo '}';
}
?>
