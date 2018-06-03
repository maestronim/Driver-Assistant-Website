<?php
	//API Url
    $url = 'http://maestronim.altervista.org/Driver-Assistant/api/user-path/read.php?user_id=maestronim&path_date=2018-06-01&offset=0';

    // Get cURL resource
    $curl = curl_init();
    // Set some options - we are passing in a useragent too here
    curl_setopt_array($curl, array(
        CURLOPT_HTTPHEADER  => array('Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1Mjc5NjIwMzEsImp0aSI6ImpkaEtRN3pPWm82ZGlCVFFseDZpMHVGR3lLK3g1TEREUTBKWjQ5TUZVSWM9IiwiaXNzIjoibWFlc3Ryb25pbS5hbHRlcnZpc3RhLm9yZyIsImV4cCI6MTUyNzk2NTYzMSwiZGF0YSI6eyJ1c2VybmFtZSI6Im1hZXN0cm9uaW0iLCJlbWFpbCI6Im1pY2hlbGVtYWVzdHJvbmk5QGdtYWlsLmNvbSJ9fQ.IjKUZo79WBPohS1zccp0kE4680lvldmcISfBrBc_w5FXLG97egP-9IV2fNTEc2HXjSNIahsugURV-qBD0v4Ihw'),
        CURLOPT_URL => $url
    ));
    // Send the request & save response to $resp
    $resp = curl_exec($curl);
    // Close request to clear up some resources
    curl_close($curl);

    echo $resp;
?>
