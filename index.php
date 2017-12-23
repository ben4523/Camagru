<?php
include('./config/setup.php');
if (isset($_SESSION['start']))
	header('Location: news.php');
else
{
	include('./theme/header.php');
	print('
		<section class="connection_session" style="height: 100%;">
			<div>
				<p id="warning_info_pass" class="warning_error" style="color: red;display:none;"></p>
			 	<div class="connection_R1">
			 		<div id="header_connection">
			 			<h1>Camagru</h1>
			 			<form method="post" action="requests.php?func=login">
			 				<input type="text" name="cc_email" id="cc_username" placeholder="'.INDEX_R1.'">
			 				<input type="password" name="cc_password" id="cc_password" placeholder="'.INDEX_R2.'">
			 				<button type="button" class="send_connection" onclick="javascript:AJAXSend(this)">'.INDEX_R3.'</button>
			 				<a id="forgot_pass_link">'.INDEX_R4.'</a>
			 			</form>
			 		</div>
			 		<div id="footer_connection" class="no_account">
			 			<p>'.INDEX_R5.' <a style="color: #3897f0; cursor:pointer; text-decoration: none;">'.INDEX_R6.'</a></p>
			 		</div>
			 		<div id="register_user" style="display: none;">
			 			<form method="post" action="requests.php?func=register" >
			 				<h3>'.INDEX_R7.'</h3>
			 				<input type="text" name="cc_username" placeholder="'.INDEX_R8.'">
			 				<input type="text" name="cc_name" placeholder="'.INDEX_R9.'">
			 				<input type="email" name="cc_email" id="cc_email" placeholder="'.INDEX_R10.'">
			 				<input type="password" name="cc_password" placeholder="'.INDEX_R11.'">
			 				<button type="button" onclick="javascript:AJAXSend(this)">'.INDEX_R12.'</button>
			 			</form>
			 		</div>
			 		<div id="footer_connection" class="with_account" style="display: none;">
			 			<p>'.INDEX_R13.' <a style="color: #3897f0; cursor:pointer; text-decoration: none;">'.INDEX_R14.'</a></p>
			 		</div>
			 		<div id="request_link_pass" style="display: none;">
			 			<form method="post" action="requests.php?func=request_pass">
			 				<h3>'.INDEX_R4.'</h3>
			 				<input type="text" name="cc_email" placeholder="'.INDEX_R1.'">
			 				<button type="button" onclick="javascript:AJAXSend(this)">'.INDEX_R15.'</button>
			 				<a id="return_to_connect" style="cursor: pointer;">'.INDEX_R16.'</a>
			 			</form>
			 		</div>
			 	</div>
			 	<p id="warning_info_pass" class="warning_ok" style="color: green;display:none;"></p>
			</div>
		</section>
	');
}
?>

<script type="text/javascript">
	var reset_pass_button = document.querySelector('.connection_session div#header_connection #forgot_pass_link');
  	var return_to_connect = document.querySelector('.connection_session div#request_link_pass #return_to_connect');
  	var register_button = document.querySelector('.connection_session div#footer_connection.no_account a');
  	var return_to_connect_V1 = document.querySelector('.connection_session div#footer_connection.with_account');
  	return_to_connect.addEventListener('click', function(ev){
  		var footer_connection_d = document.querySelector('.connection_session div#footer_connection');
  		var header_connection_d = document.querySelector('.connection_session div#header_connection');
  		var request_link_pass_d = document.querySelector('.connection_session div#request_link_pass');
  		var register_user = document.querySelector('.connection_session div#register_user');
    	footer_connection_d.style.display = "flex";
    	header_connection_d.style.display = "flex";
    	request_link_pass_d.style.display = "none";
    }, false);
  	reset_pass_button.addEventListener('click', function(ev){
  		var footer_connection_d = document.querySelector('.connection_session div#footer_connection');
  		var header_connection_d = document.querySelector('.connection_session div#header_connection');
  		var request_link_pass_d = document.querySelector('.connection_session div#request_link_pass');
    	footer_connection_d.style.display = "none";
    	header_connection_d.style.display = "none";
    	request_link_pass_d.style.display = "flex";
    }, false);
    register_button.addEventListener('click', function(ev){
    	var header_connection_d = document.querySelector('.connection_session div#header_connection');
    	var register_user = document.querySelector('.connection_session div#register_user');
  		var footer_connection_d = document.querySelector('.connection_session div#footer_connection.no_account');
  		var footer_connection_d_R1 = document.querySelector('.connection_session div#footer_connection.with_account');
  		header_connection_d.style.display = "none";
  		register_user.style.display = "flex";
    	footer_connection_d.style.display = "none";
    	footer_connection_d_R1.style.display = "flex";
    }, false);
    return_to_connect_V1.addEventListener('click', function(ev){
    	var header_connection_d = document.querySelector('.connection_session div#header_connection');
    	var register_user = document.querySelector('.connection_session div#register_user');
  		var footer_connection_d = document.querySelector('.connection_session div#footer_connection.no_account');
  		var footer_connection_d_R1 = document.querySelector('.connection_session div#footer_connection.with_account');
  		header_connection_d.style.display = "flex";
  		register_user.style.display = "none";
    	footer_connection_d.style.display = "flex";
    	footer_connection_d_R1.style.display = "none";
    }, false);
</script>