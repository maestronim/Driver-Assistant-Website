<?php
use \Firebase\JWT\JWT;

function validate_token($authHeader) {
  require_once '../../vendor/autoload.php';
  require_once 'config.php';

  /*
  * Look for the 'authorization' header
  */
  if ($authHeader) {
    /*
    * Extract the jwt from the Bearer
    */
    list($jwt) = sscanf($authHeader, 'Bearer %s');

    if ($jwt) {
      try {
        /*
        * decode the jwt using the key from config file
        */
        $secretKey = base64_decode($key);

        $token = JWT::decode($jwt, $secretKey, array('HS512'));

        return true;

      } catch (Exception $e) {
        /*
        * the token was not able to be decoded.
        * this is likely because the signature was not able to be verified (tampered token)
        */
        //echo "the token was not able to be decoded.";
        return false;
      }
    } else {
      /*
      * No token was able to be extracted from the authorization header
      */
      //echo "No token was able to be extracted from the authorization header";
      return false;
    }
  } else {
    /*
    * The request lacks the authorization token
    */
    //echo "The request lacks the authorization token";
    return false;
  }
}
