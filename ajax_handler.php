<?php

ob_start();
$array_ip=$_GET['array_ip'];
$api_token=$_GET['api_token'];
$query=$_GET['query_type'];


//session setup function

function send_command_to_array(bool $session_start,string $url, string $data)
{
if($session_start==true){
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
}
else
{
    $opts = array('http' =>
        array(
            'method'  => 'GET',
            'header'  => ['Content-type: application/json','Cookie: session=' . $_COOKIE['session']],
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
    
    //print_r($opts);

}
    $context = stream_context_create($opts);

        $response = file_get_contents($url, true, $context);
        //var_dump($http_response_header);
 if($session_start==true){       
//take response header and create a cookie
$cookie_info_string=$http_response_header[6];
//echo "cookie string:{$cookie_info_string}";
$cookie_temp_array = explode('session=',$cookie_info_string);
$cookie_temp_string = $cookie_temp_array[1];
$cookie_temp_array = explode(';',$cookie_temp_string);
$cookie_temp_string = $cookie_temp_array[0];
setcookie('session', $cookie_temp_string, time()+1800, '/');
//echo "<br><br>cookie set!{$cookie_temp_string}";
}
       

    return $response;
}

if(!isset($_COOKIE['session'])) {
$session_url="https://{$array_ip}/api/1.0/auth/session";
$data = "{\"api_token\":\"{$api_token}\"}";
//echo "Session input:";
//echo "url: $session_url data: $data";

$session_response = send_command_to_array(true,$session_url, $data);


//echo "<br><br>Session response:";
//var_dump($session_response);
//send request to array

//take in vars
}


switch ($query){
	case "vols":
 		$query_url = "https://{$array_ip}/api/1.17/volume";
		 $data = "{\"pending\":false,\"limit\":10}";
	break;	
	case "erad_vols":
 		$query_url = "https://{$array_ip}/api/1.17/volume";
		 $data = "{\"pending_only\":true,\"limit\":10}";
	break;	
	case "snaps":
 		$query_url = "https://{$array_ip}/api/1.17/volume";
		 $data = "{\"snap\":true,\"limit\":10}";
	break;	
	case "erad_snaps":
 		$query_url = "https://{$array_ip}/api/1.17/volume";
		 $data = "{\"snap\":true,\"pending\":true,\"limit\":10}";
	break;	
	

}
//$query_url = "https://{$array_ip}/api/1.17/volume";
//$data = "{\"pending\":false,\"limit\":10}";
  //$data='';   
$query_response = send_command_to_array(false,$query_url, $data);
$response = json_decode($query_response,true);

$query = "URL:" . $query_url . " <br>Body:" . $data;

array_unshift($response , $query);


echo(json_encode($response));


ob_end_flush();


?>