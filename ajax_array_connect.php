<?php

//buffer the output until it's good and ready!
ob_start();

//take in the array's IP and api_token from the web form.
$array_ip=$_GET['array_ip'];
$api_token=$_GET['api_token'];

//Values from testing:
//$array_ip="10.225.112.10";
//$api_token="17370fb2-0ce2-f724-58df-6f7ce8df2e26"; 

//function created to send the commands to the array
function send_command_to_array(bool $session_start,string $url, string $data)
{
$allgood=false;
    //create the headers for the request
    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/json',
            'content' => $data,
            'ignore_errors' => true
        ),
       'ssl' =>
        array(
       		'verify_peer'  => false,
       		'verify_peer_name'  => false,
       		'allow_self_signed'  => true,
       		'security_level'  => 0
       ) 
        
    );


    $context = stream_context_create($opts);
//send the request to the array and bring back the response
//note that PHP uses the dsame functions as it would to get a file from a webserver!
        $response = file_get_contents($url, true, $context);
       
     
//take response header and create a cookie
//we need to keep the response string for authentication, we are the originating client (this is pretty secure)
$cookie_info_string=$http_response_header[6];
//echo "cookie string:{$cookie_info_string}";
$cookie_temp_array = explode('session=',$cookie_info_string);
$cookie_temp_string = $cookie_temp_array[1];
$cookie_temp_array = explode(';',$cookie_temp_string);
$cookie_temp_string = $cookie_temp_array[0];

      

    return $cookie_temp_string;
}

//let's put the URL for the request together (where we are sending the request)
$session_url="https://{$array_ip}/api/1.0/auth/session";
//and let's put together the body of the request (the details of what we are requesting).
$data = "{\"api_token\":\"{$api_token}\"}";

//let's run out function (above) to get the response from the array.
$array_response = send_command_to_array(true,$session_url, $data);

$response=array('cookie' => $array_response);

//let's display the results in a JSON standard format.  The Ajax request can read it and pass it into the main page.
//ajax simply means the main page will not have to completely reload.
echo(json_encode($response));

//we can stop buffering the output now that we are ready, and send it back.
ob_end_flush();
?>

