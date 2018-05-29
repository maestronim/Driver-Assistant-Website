<?php
	function getRequest($url) {
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
          CURLOPT_RETURNTRANSFER => 1,
          CURLOPT_URL => $url
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);

        // Close request to clear up some resources
        curl_close($curl);

        return $resp;
    }
    
    
    function postRequest($url, $jsonData) {
    	//Initiate cURL.
        $ch = curl_init($url);
        
        //Encode the array into JSON.
        $jsonDataEncoded = json_encode($jsonData);
        
        //Tell cURL that we want to send a POST request.
        curl_setopt($ch, CURLOPT_POST, 1);
        
        //Attach our encoded JSON string to the POST fields.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        
        //Set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        //Execute the request
        $result = curl_exec($ch);
        
        return $result;
    }
    
    function postRedirect($dest) {
        $url = $params = '';
        if(strpos($dest,'?')) { 
        	list($url,$params) = explode('?',$dest,2); }
        else {
        	$url = $dest;
       	}
        echo "<form id='the-form' 
              method='post'
              action=" . $url . ">";
        foreach( explode('&',$params) as $kv )
        {
          if( strpos($kv,'=') === false ) { continue; }
          list($k,$v) = explode('=',$kv,2);
          echo "<input type='hidden' name=" . $k . " value=" . $v . ">\n";
        }
        echo "</form>
        <script type='text/javascript'>
        document.getElementById('the-form').submit();
        </script>";
    }
?>