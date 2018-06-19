<?php
	//API Url
    $url = 'http://maestronim.altervista.org/Driver-Assistant/api/car-parameters/read.php?user_id=maestronim&path_id=1&offset=18';

    // Get cURL resource
    $curl = curl_init();
    // Set some options - we are passing in a useragent too here
    curl_setopt_array($curl, array(
        CURLOPT_HTTPHEADER  => array('Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1MjkzOTk3NjMsImp0aSI6IkttTlEwVm1PMktlWXJVVGpRYzRKc1pyYlFzNGE4Z0tFZjdxQktrK3JFWXM9IiwiaXNzIjoibWFlc3Ryb25pbS5hbHRlcnZpc3RhLm9yZyIsImV4cCI6MTUyOTQ4NjE2MywiZGF0YSI6eyJ1c2VybmFtZSI6Im1hZXN0cm9uaW0ifX0.A4SAoYWNQv9tjfQe6lcL-Yh2AYu-B23cnN291L4JIdPNJCR-3zeEaAwvLQ9iTP8EeAl-JZ0eCjE1xdPdyi1K8g'),
        CURLOPT_URL => $url
    ));
    // Send the request & save response to $resp
    $resp = curl_exec($curl);
    // Close request to clear up some resources
    curl_close($curl);

    echo $resp;
?>
