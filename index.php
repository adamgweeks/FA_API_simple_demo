<!-- main page, with the form in it -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="Content-Style-Type" content="text/css">
        <meta http-equiv="Content-Script-Type" content="text/javascript">

        <title>Simple FA Restful API demo</title>
        
        <link rel="stylesheet" href="css/base.css" type="text/css"/>
<!-- Include the necessary Javascript libraries to make the page work! -->
        <script src="js/jquery-1.2.6.js" type="text/javascript"></script>
        <script src="js/ui.core.js" type="text/javascript"></script>
        <script src="js/jquery.cookie.js"></script>
        <script type="text/javascript">
//when the page is completely loaded, run this:
 $(document).ready(function(){  
    //hide the empty results from the user
 	$("#request_type").hide();
 	$("#raw_info").hide();
 	$("#formatted_info").hide();
 	
//clear text boxes (when clicked in) 
$("#array_ip").focus(function(){
if ($("#array_ip").val()=='0.0.0.0'){$("#array_ip").val('');}
});
$("#array_ip").blur(function(){
if ($("#array_ip").val()==''){$("#array_ip").val('0.0.0.0');}
});

$("#api_token").focus(function(){
if ($("#api_token").val()=='token'){$("#api_token").val('');}
});
$("#api_token").blur(function(){
if ($("#api_token").val()==''){$("#api_token").val('token');}
});
   
//Query button makes AJAX request (passesd to ajax_handler.php)   
$("#submit").click(function(){
$("#formatted_info tr").slice(1).remove();
//alert("query for is " + $("#query_for").val());
  $.ajax({url: "./ajax_handler.php",data: { array_ip: $("#array_ip").val(), api_token: $("#api_token").val(),query_type: $("#query_for").val()},success: function(result){
    var return_obj=JSON.parse(result);
    var request = return_obj[0];
    var response = '';
//write each line out in the table    
$(return_obj).each(function(index,value){ 
	if(index>0){  response_add = JSON.stringify(value); response = response + response_add; 
	GB_size = value['size']/(1024*1024*1024);
	$('#formatted_info').append('<tr><td>' + value['name'] + '</td><td>' + value['source'] + '</td><td>' + value['created'] + '</td><td>' + value['serial'] + '</td><td>' + GB_size + '</td></tr>');
	}
});

	$("#request").html(request); 
    $("#response").html(response);

 	$("#raw_info").show();
 	$("#formatted_info").show();
    //console.log(result);
  }});
});
//user has requested to start the session with the FA, pass it on to the ajax_array_connect.php page:
$("#establish").click(function(){
$.removeCookie('session'); 
  $.ajax({url: "./ajax_array_connect.php",data: { array_ip: $("#array_ip").val(), api_token: $("#api_token").val()},dataType: 'json',success: function(result){
if(result.cookie.length>10){
$.cookie('session', result.cookie);
    //console.log(result.cookie);
    $("#connect_table").css('background-color', 'green');
     	$("#request_type").show(); 	
}
  }});
});


});
        </script>
    </head>

    <body>
        <h1>Simple FA Restful API demo</h1>
        
<h3>Note that all responses are limited to 10 objects max (to keep responses reasonable!)</h3> 
       
<form>
<label for="connect_table">Establish connection to FA before sending requests.</label>
<table border='1' bgcolor='red' id="connect_table">
<tr>
<th>FA IP:</th><th>FA API token:</th><th>Establish session</th>
</tr>
<tr>
<td align="center" valign="middle"><input type="text" id="array_ip" value="0.0.0.0"></input></td>
<td align="center" valign="middle"><input type="text" id="api_token" value="token"></input></td>
<td align="center" valign="middle"><button type="button" id="establish">Connect</button></td>
</table>

<p><p><p>

<label for="request_type">Information requested:</label>
<table border='1' id="request_type">
<tr>
<th>Show me:</th><th>Go!</th>
</tr>
<tr>
<td align="center" valign="middle">

<select name="query_for" id="query_for">
  <option value="vols">FA Volumes</option>
  <option value="erad_vols">FA Eradicated volumes</option>
  <option value="snaps">FA Snapshots</option>
  <option value="erad_snaps">FA Eradicated Snapshots</option>
</select>
</td>
<td align="center" valign="middle"><button type="button" id="submit">Query</button></td>
</tr>
</table>
</form>

<p><p><p>

<label for="formatted_info">Nicely formatted info:</label>
<table border='1' id="formatted_info">
<tr>
<th>Name</th><th>Source</th><th>Created</th><th>Serial</th><th>Size (GiB)</th>
</tr>
</table>

<p><p><p>

<label for="raw_info">Raw request and response data:</label>
<table border='1' id="raw_info">
<tr>
<th>Command sent:</th><th>Response:</th>
</tr>
<tr>
<td align="center" valign="middle" id="request">sent</td><td align="center" valign="middle" id="response">received</td>
</tr>
</table>


<p><p><p>
        <div>See <a href="https://support.purestorage.com/FlashArray/PurityFA/Purity_FA_REST_API">https://support.purestorage.com/FlashArray/PurityFA/Purity_FA_REST_API</a> for more information.</div>
        
        
    </body>
</html>
