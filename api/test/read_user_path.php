<?php
	//API Url
    $url = 'http://maestronim.altervista.org/Driver-Assistant/api/user-path/read.php?user_id=maestronim&path_date=2018-06-01&offset=0';

    // Get cURL resource
    $curl = curl_init();
    // Set some options - we are passing in a useragent too here
    curl_setopt_array($curl, array(
        CURLOPT_HTTPHEADER  => array('Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1MjgxOTE2MzAsImp0aSI6IktaNjZWdmpuK25WQzQyUHNzV3RjUTFVc1wvSmVjQzFOTTI0QmYrV3JMQUVnPSIsImlzcyI6Im1hZXN0cm9uaW0uYWx0ZXJ2aXN0YS5vcmciLCJleHAiOjE1MjgxOTUyMzAsImRhdGEiOnsidXNlcm5hbWUiOiJtYWVzdHJvbmltIiwiZW1haWwiOiJtaWNoZWxlbWFlc3Ryb25pOUBnbWFpbC5jb20ifX0.zDAMGrkRQ0yUVIASJX7oq250YZGpnRJIt-3DxUVvQ59nKtDZJQbQUtMhsMnHPlN6Gk34LvHJYAo_-rZ2OlQmRw'),
        CURLOPT_URL => $url
    ));
    // Send the request & save response to $resp
    $resp = curl_exec($curl);
    // Close request to clear up some resources
    curl_close($curl);

    echo $resp;
?>
