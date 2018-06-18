<?php
	//API Url
    $url = 'http://maestronim.altervista.org/Driver-Assistant/api/car-parameters/count.php?user_id=maestronim&path_id=2';

    // Get cURL resource
    $curl = curl_init();
    // Set some options - we are passing in a useragent too here
    curl_setopt_array($curl, array(
        CURLOPT_HTTPHEADER  => array('Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1MjkzMTU0OTUsImp0aSI6IklJZU9WT1dKc0lYNXl5azdJcm9BR20zeGY4SUpIcHNtc0pMcXNhb0I1eW89IiwiaXNzIjoibWFlc3Ryb25pbS5hbHRlcnZpc3RhLm9yZyIsImV4cCI6MTUyOTQwMTg5NSwiZGF0YSI6eyJ1c2VybmFtZSI6Im1hZXN0cm9uaW0ifX0._vSIQo3v21sKuE7V0GLO7_X_TJSkA64mzQpY_PLmNv3MWlR1PaIR6t8ZuQ2gXI3pf7nxn0xQaIRAk-DxGB3Y9w'),
        CURLOPT_URL => $url
    ));
    // Send the request & save response to $resp
    $resp = curl_exec($curl);
    // Close request to clear up some resources
    curl_close($curl);

    echo $resp;
?>
