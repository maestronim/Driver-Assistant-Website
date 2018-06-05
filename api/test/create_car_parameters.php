<?php
	//API Url
    $url = 'http://maestronim.altervista.org/Driver-Assistant/api/car-parameters/create.php';

    //Initiate cURL.
    $ch = curl_init($url);

    //The JSON data.
    $jsonData = array(
      "user_id" => "maestronim",
      "path_id" => "2",
      "absoluteEngineLoad" => "34.4",
      "engineLoad" => "45",
      "massAirFlow" => "54",
      "oilTemperature" => "81.3",
      "RPM" => "1500",
      "throttlePosition" => "27.6",
      "airFuelRatio" => "12",
      "consumptionRate" => "23",
      "fuelLevel" => "31.8",
      "fuelTrim" => "34",
      "widebandAirFuelRatio" => "56",
      "barometricPressure" => "135.6",
      "fuelPressure" => "90",
      "fuelRailPressure" => "105.6",
      "intakeManifoldPressure" => "45",
      "airIntakeTemperature" => "78",
      "ambientAirTemperature" => "27.2",
      "engineCoolantTemperature" => "66"
    );

    //Encode the array into JSON.
    $jsonDataEncoded = json_encode($jsonData);

    //Tell cURL that we want to send a POST request.
    curl_setopt($ch, CURLOPT_POST, 1);

    //Attach our encoded JSON string to the POST fields.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

    $header = array(
      'Accept: application/json',
      'Content-Type: application/json',
      'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1MjgyMDMxNzAsImp0aSI6IlVpTDI0Zko0VFpzQTB2cFhqb3ZvMmpkZUZhZGhHTEJHZWd3NTV1YzRWZFU9IiwiaXNzIjoibWFlc3Ryb25pbS5hbHRlcnZpc3RhLm9yZyIsImV4cCI6MTUyODI4OTU3MCwiZGF0YSI6eyJ1c2VybmFtZSI6Im1hZXN0cm9uaW0ifX0.5vq_RNV_yqY-le7X_4j6XEd1oKS6N7T9IOz6nsFTMXkopoms2nu2YL5eUOUFU_bIxTkMyTeS-PSWq2MtX-sBtw'
    );

    //Set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    /*// Will return the response, if false it print the response
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);*/

    //Execute the request
    $result = curl_exec($ch);
?>
