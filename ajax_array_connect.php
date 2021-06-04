<?php

ob_start();

$array_ip=$_GET['array_ip'];
$api_token=$_GET['api_token'];

//$array_ip="10.225.112.10";
//$api_token="17370fb2-0ce2-f724-58df-5f7ce8df2e26"; 


function send_command_to_array(bool $session_start,string $url, string $data)
{
$allgood=false;
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

        $response = file_get_contents($url, true, $context);
        //var_dump($http_response_header);
     
//take response header and create a cookie
$cookie_info_string=$http_response_header[6];
//echo "cookie string:{$cookie_info_string}";
$cookie_temp_array = explode('session=',$cookie_info_string);
$cookie_temp_string = $cookie_temp_array[1];
$cookie_temp_array = explode(';',$cookie_temp_string);
$cookie_temp_string = $cookie_temp_array[0];

      

    return $cookie_temp_string;
}

$session_url="https://{$array_ip}/api/1.0/auth/session";
$data = "{\"api_token\":\"{$api_token}\"}";

$array_response = send_command_to_array(true,$session_url, $data);

$response=array('cookie' => $array_response);

echo(json_encode($response));


ob_end_flush();
?>

