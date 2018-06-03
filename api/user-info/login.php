<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../objects/user_info.php';
require_once '../../vendor/autoload.php';
require_once '../token/config.php';

use \Firebase\JWT\JWT;

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate object
$user_info = new UserInfo($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set user property values
$user_info->username = $data->username;
$user_info->password = $data->password;

// check if the credentials are valid
$stmt = $user_info->login();

// check if more than 0 record found
if($stmt->rowCount() > 0) {
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if(password_verify($data->password, $row['password'])) {
    $tokenId    = base64_encode(mcrypt_create_iv(32));
    $issuedAt   = time();
    $expire     = $issuedAt + 3600;            // Adding 60 seconds
    $serverName = $_SERVER['SERVER_NAME']; // Retrieve the server name from config file

    /*
    * Create the token as an array
    */
    $data = [
      'iat'  => $issuedAt,         // Issued at: time when the token was generated
      'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
      'iss'  => $serverName,       // Issuer
      'exp'  => $expire,           // Expire
      'data' => [                  // Data related to the signer user
        'username' => $row['username'],
        'email' => $row['email']
      ]
    ];

    /*
    * Extract the key, which is coming from the config file.
    *
    * Best suggestion is the key to be a binary string and
    * store it in encoded in a config file.
    *
    * Can be generated with base64_encode(openssl_random_pseudo_bytes(64));
    *
    * keep it secure! You'll need the exact key to verify the
    * token later.
    */

    $secretKey = base64_decode($key);

    /*
    * Encode the array to a JWT string.
    * Second parameter is the key to encode the token.
    *
    * The output string can be validated at http://jwt.io/
    */
    $jwt = JWT::encode(
      $data,      //Data to be encoded in the JWT
      $secretKey, // The signing key
      'HS512'     // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
    );

    $responseArray = array(
      "valid" => "yes",
      "message" => "Valid credentials",
      "jwt" => $jwt
    );

    echo json_encode($responseArray);
    } else{
      echo '{';
        echo '"valid": "no",';
        echo '"message": "Invalid credentials."';
        echo '}';
      }
    } else{
      echo '{';
        echo '"valid": "no",';
        echo '"message": "Invalid credentials."';
        echo '}';
      }
      ?>
