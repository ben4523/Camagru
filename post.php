<?php
include("./config/setup.php");
include('./theme/header.php');

$query= $pdo->prepare("SELECT * FROM user WHERE id=:id");
$query->execute(array('id' => $_SESSION['user_id']));
if (!($val = $query->fetch()))
{
	session_destroy();
	header('Location: ' .$_SESSION['base_url']);
}
print('<section class="add_post">
	  	<div class="add_post_r1">
	  		<div class="add_post_r1_left" style="width: 75%;">
	  			<div class="add_post_r1_left_top">
	  				<span>Séléctionner une image :</span>
	  				<form method="post" action="requests.php?func=create_post_img&type=" enctype="multipart/form-data">
	  					<div>
		  					<label id="add_post_r1_left_top_label">
							    <input type="radio" name="background" id="backsel" checked value="1+250+180+81+0+0+230+165"/>
							    <img src="./theme/img/croquis_pictures/1_mini.jpg">
							</label>
							<label id="add_post_r1_left_top_label">
							    <input type="radio" name="background" id="backsel" value="2+330+128+51+0+0+330+248"/>
							    <img src="./theme/img/croquis_pictures/2_mini.jpg">
							</label>
							<label id="add_post_r1_left_top_label">
							    <input type="radio" name="background" id="backsel" value="3+240+165+57+0+0+240+218"/>
							    <img src="./theme/img/croquis_pictures/3_mini.jpg">
							</label>
							<label id="add_post_r1_left_top_label">
							    <input type="radio" name="background" id="backsel" value="4+290+148+82+0+0+290+218"/>
							    <img src="./theme/img/croquis_pictures/4_mini.jpg">
							</label>
						</div>
	  			</div>
	  			<div class="add_post_r1_left_bottom">
	  				<span id="pre_loader_cam"></span>
	  				<div>
	  					<div class="vid_flash" style="display:none;"></div>
	  					<div class="pre_vid"><p style="display:none;"></></div>
	  					<video id="video" style="border: 5px solid #000; display: none;"></video></div>
	  				<span id="error_cam" style="display: none;"><a title="erreur"></a>Oups ! Il y à un probleme avec votre caméra !</span>
					<canvas style="display: none" id="canvas"></canvas>
					<div>
						<div class="upload_img">
							<input type="file" name="file1" id="file-2" class="inputfile inputfile-2" onchange="javascript:AJAXSend_img(this, 1)"/>
							<label for="file-2"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Choisir Fichier&hellip;</span></label>
						</div>
						<input id="cpt_1" type="hidden" name="file2">
						<span id="ou_span">OU</span>
						<button id="startbutton" type="button">Prendre une photo</button>
					</form>
					</div>
					<p id="warning_info_pass" class="warning_ok" style="color: green;display:none;"></p>
					<p id="warning_info_pass" class="warning_error" style="color: red;display:none;"></p>
	  			</div>
	  		</div>
	  		<div class="add_post_r1_right" style="width: 25%;">');
			if (isset($_SESSION['pre_post']) && $_SESSION['pre_post'] != '') {
				$tab_img = explode(',', $_SESSION['pre_post']);
				$y = count($tab_img);
				for ($i = 0; $i < $y; $i++)
					echo "<img src='".$tab_img[$i]."-thumb.png' onclick='javascript:open_share_box(this, \"".$tab_img[$i].".png\")'/>";
			}
	  		print('</div>
	  	</div>
	  </section>
	  <div class="result_create_picture">
	  	<canvas></canvas>
	  	<div class="result_create_picture_r1">
	  		<h2>Souhaiter vous Partager ?</h2>
	  		<div>
	  			<img id="photo" style="width: 100%;">
	  			<span class="border_img"></span>
	  			<form method="post"action="requests.php?func=publish_post">
	  				<input id="cpt_2" type="hidden" name="image_publish">
	  				<textarea aria-label="Ajouter un commentaire..." class="add_comment_post_area" placeholder="Ajouter un commentaire..." style="height: 18px;" name="comment_post"></textarea>
	  				<div class="result_create_picture_r2">
	  					<button type="button" name="submit_share" id="submit_button" onclick="javascript:AJAXSend(this)">Partager</button>
	  			</form>
			  			<span id="ou_span">OU</span>
			  			<button type="button" id="cancelbutton">Recommencer</button>
			  		</div>
	  		</div>
	  		<p id="warning_info_pass" class="warning_ok_sub" style="color: green;display:none;"></p>
			<p id="warning_info_pass" class="warning_error_sub" style="color: red;display:none;"></p>
	  	</div>
	  </div>');
include('./theme/footer.php');
?>

<script type="text/javascript">
  	(function() {
	    var streaming   = false,
	      video     = document.querySelector('#video'),
	      error           = document.querySelector('#error_cam'),
	      cover     = document.querySelector('#cover'),
	      canvas      = document.querySelector('#canvas'),
	      photo     = document.querySelector('#photo'),
	      startbutton   = document.querySelector('#startbutton'),
	      cpt_1     = document.querySelector('#cpt_1'),
	      width     = 520,
	      height      = 0;
	    navigator.getMedia  = ( navigator.getUserMedia ||
	              navigator.webkitGetUserMedia ||
	              navigator.mozGetUserMedia ||
	              navigator.msGetUserMedia);
	    function sleep(milliseconds) {
	      var start = new Date().getTime();
	        for (var i = 0; i < 1e7; i++) {
	          if ((new Date().getTime() - start) > milliseconds){
	            break;
	        }
	      }
	    }
	    navigator.getMedia(
	      {
	        video: true,
	        audio: false
	      },
	      function(stream) {
	        if (navigator.mozGetUserMedia) {
	          video.mozSrcObject = stream;
	        } else {
	          var vendorURL = window.URL || window.webkitURL;
	          video.src = vendorURL.createObjectURL(stream);
	        }
	        video.play();
	        setTimeout(function(){
	          document.querySelector('#pre_loader_cam').style.display = "none";
	          video.style.display = "block";
	        },1000); 
	      },
	      function(err) {
	        document.querySelector('#pre_loader_cam').style.display = "none";
	        error.style.display = "block";
	        video.style.display = "none";
	        startbutton.style.display = "none";
	        document.querySelector('#ou_span').style.display = "none";
	      }
	    );
	    video.addEventListener('canplay', function(ev){
	      if (!streaming) {
	        height = video.videoHeight / (video.videoWidth/width);
	        video.setAttribute('width', width);
	        video.setAttribute('height', height);
	        canvas.setAttribute('width', width);
	        canvas.setAttribute('height', height);
	        streaming = true;
	      }
	    }, false);
	    function takepicture() {
	      canvas.width = width;
	      canvas.height = height;
	      canvas.getContext('2d').drawImage(video, 0, 0, width, height);
	      var data = canvas.toDataURL('image/png');
	      cpt_1.setAttribute('value', data);
	    }
	    startbutton.addEventListener('click', function(ev){
	      	display_number = document.querySelector('.pre_vid p');
	      	div_flash = document.querySelector('.add_post_r1_left_bottom .vid_flash');
	     	setTimeout(function(){
				display_number.innerHTML = "3";
				display_number.style.display = "block";
				setTimeout(function(){
					display_number.style.display = "none";
					display_number.innerHTML = "2";
					display_number.style.display = "block";
					setTimeout(function(){
						display_number.style.display = "none";
						display_number.innerHTML = "1";
						display_number.style.display = "block";
						setTimeout(function(){
							div_flash.style.display = "block";
							setTimeout(function(){
								div_flash.style.opacity = "0.5";
								setTimeout(function(){
									div_flash.style.opacity = "1";
									setTimeout(function(){
										div_flash.style.display = "none";
									}, 50);
								}, 150);
							}, 100);
							takepicture();
					      	AJAXSend_img(startbutton, 2);
					      	display_number.style.display = "none";
					    }, 1000);
					}, 1000);
				}, 1000);
			}, 1000);
	    }, false);
	})();

	img_push = document.querySelector('.add_post_r1_right img');
	div_result_share = document.querySelector('.result_create_picture');
	function open_share_box (self_id, url) {
		div_result_share.querySelector('#photo').src = url;
		div_result_share.querySelector('#cpt_2').value = url;
		div_result_share.style.visibility = "visible";
		div_result_share.style.display = "flex";
	}
	div_result_share.querySelector('#cancelbutton').addEventListener('click', function(ev){
	    div_result_share.style.visibility = "hidden";
	    div_result_share.style.display = "none";
	}, false);
</script>