<?php
	//API Url
    $url = 'http://maestronim.altervista.org/Driver-Assistant/api/car-parameters/create.php';

    //Initiate cURL.
    $ch = curl_init($url);

    //The JSON data.
    $jsonData = array(
      "user_id" => "maestronim",
      "path_date" => "2018-06-01 12:26:13",
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

    //Set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    /*// Will return the response, if false it print the response
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);*/

    //Execute the request
    $result = curl_exec($ch);
?>
