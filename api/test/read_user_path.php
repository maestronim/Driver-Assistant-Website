<?php
	//API Url
    $url = 'http://maestronim.altervista.org/Driver-Assistant/api/user-path/read.php?user_id=maestronim&path_date=2018-06-01&offset=0';

    // Get cURL resource
    $curl = curl_init();
    // Set some options - we are passing in a useragent too here
    curl_setopt_array($curl, array(
        CURLOPT_HTTPHEADER  => array('Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1MjgwNDg1NjEsImp0aSI6InAxXC95XC94clU1Z2ZMeHYxbE8yRmV4aXdMeFg4YnlEaktkcFRrbWljY040ND0iLCJpc3MiOiJtYWVzdHJvbmltLmFsdGVydmlzdGEub3JnIiwiZXhwIjoxNTI4MDUyMTYxLCJkYXRhIjp7InVzZXJuYW1lIjoibWFlc3Ryb25pbSIsImVtYWlsIjoibWljaGVsZW1hZXN0cm9uaTlAZ21haWwuY29tIn19.DAas4SpU7tIqV7hzuVsr7NLEayaOWhpMUaqiuxUb3iwP15van42ls4xLcM0G4aE0yXsZhzQ1txUFhiy8Qhgw3w'),
        CURLOPT_URL => $url
    ));
    // Send the request & save response to $resp
    $resp = curl_exec($curl);
    // Close request to clear up some resources
    curl_close($curl);

    echo $resp;
?>
