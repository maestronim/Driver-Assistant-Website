<?php
	//API Url
    $url = 'http://maestronim.altervista.org/Driver-Assistant/api/user-path/count.php?user_id=maestronim&path_date=2018-06-01';

    // Get cURL resource
    $curl = curl_init();
    // Set some options - we are passing in a useragent too here
    curl_setopt_array($curl, array(
        CURLOPT_HTTPHEADER  => array('Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1MjgyMDE0MTgsImp0aSI6IjZSWXpQWGR1aTZkVE41WHY0WDBmYmc3RXZhNTI5RVRRK2I1bGloWnB6dWc9IiwiaXNzIjoibWFlc3Ryb25pbS5hbHRlcnZpc3RhLm9yZyIsImV4cCI6MTUyODIwNTAxOCwiZGF0YSI6eyJ1c2VybmFtZSI6Im1hZXN0cm9uaW0ifX0.ndr-OPajZ1MQ8M12JoZq9NEqll22Z_496b4Ml7DUR8-edqpdACu-XU6dDlzN2lBhcuKJi7NkOThL24IyWUvdZw'),
        CURLOPT_URL => $url
    ));
    // Send the request & save response to $resp
    $resp = curl_exec($curl);
    // Close request to clear up some resources
    curl_close($curl);

    echo $resp;
?>
