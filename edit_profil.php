<?php
	include("./config/setup.php");
	include('./theme/header.php');

	$query= $pdo->prepare("SELECT * FROM user WHERE id=:id");
	$query->execute(array('id' => $_SESSION['user_id']));
	if (!($val = $query->fetch()))
	{
		$url = $_SESSION['base_url'];
		session_destroy();
		header('Location: ' .$_SESSION['base_url']);
	}
	print('<section class="edit_profil">
		  	<div class="edit_profil_r1">
		  		<div id="profil_picture">
		  			<div id="profil_picture_r1">
		  				<img id="real_img_profil" src="'.$val['img_profil'].'" alt="Profil Picture">
		  				<input id="file" class="profil_input_img" name="cc_imguser" type="file" value="'.PROFIL_R1.'" style="display:none"/>
		  			</div>
		  			<div>
			  			<h1 class="cc_username" title="benbitton45">'.$val['username'].'</h1>
			  			<a class="cc_profilpicture" href="#" style="color:#3897f0; text-decoration: none;">'.PROFIL_R1.'</a>
			  		</div>
		  		</div>
		  		<div id="edit_info">
		  			<form method="post" action="requests.php?func=edit_profil">
		  				<div>
		  					<label for="cc_name">'.INDEX_R9.'</label>
		  					<input type="text" name="cc_name" value="'.$val['name'].'">
		  				</div>
		  				<div>
		  					<label for="cc_username">'.INDEX_R8.'</label>
		  					<input type="text" name="cc_username" value="'.$val['username'].'">
		  				</div>
		  				<div>
		  					<label for="cc_email">'.INDEX_R1.'</label>
		  					<input type="email" name="cc_email" value="'.$val['email'].'">
		  				</div>
		  				<div>
		  					<label for="cc_oldpassword">'.PROFIL_R2.'</label>
		  					<input type="password" name="cc_oldpassword">
		  				</div>
		  				<div>
		  					<label for="cc_newpassword">'.INDEX_R32.'</label>
		  					<input type="password" name="cc_newpassword">
		  				</div>
		  				<div>
		  					<label for="cc_lang">'.EDITPROFIL_R3.'</label>
    						<select name="cc_lang">
							  <option value="FR" '.(($val['default_lang'] == "FR") ? "selected" : "").'>Francais</option> 
							  <option value="EN" '.(($val['default_lang'] == "EN") ? "selected" : "").'>Anglais</option>
							</select>
		  				</div>
		  				<div>
		  					<label for="cc_lang">'.EDITPROFIL_R10.'</label>
    						<select name="cc_notif">
							  <option value="1" '.(($val['notification'] == "1") ? "selected" : "").'>'.EDITPROFIL_R11.'</option> 
							  <option value="0" '.(($val['notification'] == "0") ? "selected" : "").'>'.EDITPROFIL_R12.'</option>
							</select>
		  				</div>
		  				<a class="lock_user" onClick="javascript:ConfirmDelete()">'.PROFIL_R3.'</a>
		  				<button type="button" onclick="javascript:AJAXSend(this)">'.PROFIL_R4.'</button>
		  			</form>
		  			<p class="warning_error" style="color: red;display:none;"></p>
		  			<p id="warning_info_pass" class="warning_ok" style="color: green;display:none;"></p>
		  		</div>
		  	</div>
		  </section>');
	include('./theme/footer.php');
?>
<script type="text/javascript">
	var link_img = document.querySelector('.cc_profilpicture');
	var input_img = document.querySelector('.profil_input_img');
  	link_img.addEventListener('click', function(ev){
  		input_img.click();
    }, false);

	(function() {
	    function createThumbnail(file) {

	        var reader = new FileReader();

	        reader.addEventListener('load', function() {
	            var imgElement = document.querySelector('#real_img_profil');
	            imgElement.src = this.result;
	            prev.appendChild(imgElement);
	        });
	        reader.readAsDataURL(file);
	    }

	    var allowedTypes = ['png', 'jpg', 'jpeg', 'gif'],
	        fileInput = document.querySelector('.profil_input_img'),
	        prev = document.querySelector('#profil_picture_r1');

	    fileInput.addEventListener('change', function() {
	        var files = this.files,
	            filesLen = files.length,
	            imgType;

	            imgType = files[0].name.split('.');
	            imgType = imgType[imgType.length - 1];

	            if (allowedTypes.indexOf(imgType) != -1) {
	            	if (window.XMLHttpRequest)
				        xmlhttp=new XMLHttpRequest();
					else
				        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	    			xmlhttp.open("POST",'<?php echo $_SESSION['base_url']."requests.php?func=edit_profil_img" ?>',true);
				    xmlhttp.withCredentials = true;
					xmlhttp.onreadystatechange = function (oEvent) {
					    if (xmlhttp.readyState === 4) {  
					        if (xmlhttp.status === 200) {
					        	createThumbnail(files[0]);
					        	var warning_ok = document.querySelector('.warning_ok');
						        if (xmlhttp.responseText != '')
						         	warning_ok.style.display = "block";
						        warning_ok.innerHTML = xmlhttp.responseText;
						        setTimeout(function(){
									warning_ok.style.display = "none";
								}, 2000);
					        } else if (xmlhttp.status === 201) {      
					           	var warning_error_login = document.querySelector('.warning_error');
					           	if (xmlhttp.responseText != '')
					           		warning_error_login.style.display = "block";
					           	warning_error_login.innerHTML = xmlhttp.responseText;
					           	setTimeout(function(){
								    warning_error_login.style.display = "none";
								}, 2000);
					        }
					    }
					};
					var form = new FormData();
					form.append('cc_img', fileInput.files[0]);
					xmlhttp.send(form);
	            } else {
					var warning_error_login = document.querySelector('.warning_error');
		           	warning_error_login.style.display = "block";
		           	warning_error_login.innerHTML = "<?php echo EDITPROFIL_R5; ?>";
		           	setTimeout(function(){
					    warning_error_login.style.display = "none";
					}, 2000);
				}
	    });
	})();

	function ConfirmDelete() {
	   var url = "<?php echo $_SESSION['base_url'];?>requests.php?func=delete_user&token=<?php echo $val['token'];?>";
	   if (confirm("<?php echo EDITPROFIL_R8; ?>")) {
	   		if (window.XMLHttpRequest)
		        xmlhttp=new XMLHttpRequest();
			else
		        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		    xmlhttp.open("POST",url,false);
		    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xmlhttp.withCredentials = true;
			xmlhttp.onreadystatechange = function (oEvent) {
			    if (xmlhttp.readyState === 4) {  
			        if (xmlhttp.status === 200) {
			          document.location.href="<?php echo $_SESSION['base_url'];?>";
			        } else if (xmlhttp.status === 201){      
			           var warning_error_login = document.querySelector('.warning_error');
			           if (xmlhttp.responseText != '')
			           	warning_error_login.style.display = "block";
			           warning_error_login.innerHTML = xmlhttp.responseText;
			           setTimeout(function(){
						    warning_error_login.style.display = "none";
						}, 2000);
			        }
			    }  
			};
		    xmlhttp.send();
	   }
	}
</script>
