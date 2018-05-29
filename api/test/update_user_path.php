<?php
    //API Url
    $url = 'http://maestronim.altervista.org/Driver-Assistant/api/user-path/update.php';

    //Initiate cURL.
    $ch = curl_init($url);
    
    $coords = array(
    	array(23.4326364,37.5237996)
        );

    //The JSON data.
    $jsonData = array(
        "user_id" => "maestronim",
        "path_date" => "2018-05-18 22:24:57",
        "hard_braking" => "8",
        "speed_limit_exceeded" => "16",
        "dangerous_time" => "1",
        "duration" => "00:40:45",
        "coordinates" => $coords
    );

    //Encode the array into JSON.
    $jsonDataEncoded = json_encode($jsonData);

    //Tell cURL that we want to send a POST request.
    curl_setopt($ch, CURLOPT_POST, 1);

    //Attach our encoded JSON string to the POST fields.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

    //Set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 

    //Execute the request
    $result = curl_exec($ch);
?>