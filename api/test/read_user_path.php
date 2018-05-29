<?php
	//API Url
    $url = 'http://maestronim.altervista.org/Driver-Assistant/api/user-path/read.php';

    //Initiate cURL.
    $ch = curl_init($url);

    //The JSON data.
    $jsonData = array(
      "user_id" => "maestronim",
      "path_date" => "2018-05-15"
    );

    //Encode the array into JSON.
    $jsonDataEncoded = json_encode($jsonData);

    //Tell cURL that we want to send a POST request.
    curl_setopt($ch, CURLOPT_POST, 1);

    //Attach our encoded JSON string to the POST fields.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

    //Set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    /*// Will return the response, if false it print the response
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);*/

    //Execute the request
    $result = curl_exec($ch);
?>