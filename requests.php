<?php
include("./config/setup.php");
if (isset($_GET['func']))
{
	if ($_GET['func'] == "register" && isset($_POST['cc_username']) && isset($_POST['cc_password']) && isset($_POST['cc_email']) &&
		isset($_POST['cc_name']))
	{
		if ($_POST['cc_username'] != '' && $_POST['cc_password'] != '' && $_POST['cc_email'] != '' && $_POST['cc_name'] != '')
		{
			if (!preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', $_POST['cc_email'])) {
				http_response_code(201);
				echo INDEX_R20;
			}else if (strlen($_POST['cc_password']) < 6){
				http_response_code(201);
				echo INDEX_R17;
			}else{
				$query= $pdo->prepare("SELECT id FROM user WHERE username=:username OR email=:email");
				$query->execute(array('username' => $_POST['cc_username'],'email' => $_POST['cc_email']));
				if ($val = $query->fetch()){
					http_response_code(201);
					echo INDEX_R22;
				} else {
					$hash_pass = hash("whirlpool", $_POST['cc_password']);
					$token = uniqid(rand(), true);
					$query = $pdo->prepare("INSERT INTO `user`(`id`, `name`, `username`, `email`, `password`,
											`default_lang`, `active`, `token`, `img_profil`, `notification`)
						VALUES (0,:name,:username,:email,:password,:lang,0,:token,0,1)");
					$query->execute(array('name' => $_POST['cc_name'], 'username' => $_POST['cc_username'],'email' => $_POST['cc_email'],
						'password' => $hash_pass, 'lang' => $_SESSION['lang'], 'token' => $token));
					$link_mail = $_SESSION['base_url']."requests.php?func=active_account&email=".$_POST['cc_email']."&token=".$token;
					send_mail ($_POST['cc_email'], INDEX_R24, INDEX_R23 .$link_mail, INDEX_R23."<br/><b>".$link_mail."</b>");
					echo INDEX_R21;
				}
			}
		}
		else
		{
			http_response_code(201);
			echo INDEX_R19;
		}
	}else if ($_GET['func'] == "active_account" && isset($_GET['email']) && isset($_GET['token'])) {
		include('./theme/header.php');
		if (preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', $_GET['email']) && $_GET['token'] != '')
		{
			$query = $pdo->prepare("SELECT id FROM `user` WHERE `email` = :email AND `token` = :token");
			$query->execute(array('email' => $_GET['email'], 'token' => $_GET['token']));
			if ($val = $query->fetch())
			{
				$token = uniqid(rand(), true);
				$query = $pdo->prepare("UPDATE `user` SET `active`= '1', `token`= :token WHERE `id` = :id");
				$query->execute(array('id' => $val[0],'token' => $token));
				echo '<div style="display:flex;justify-content: center;align-items: center;height: 
					100%;font-size: 20px;color: green; flex-flow:column;"><p>'.INDEX_R36.'<br/></p>';
			}
			else
				echo '<div style="display:flex;justify-content: center;align-items: center;height: 
				100%;font-size: 20px;color: red;"><p>'.INDEX_R25.'</p>';
		}
		else
			echo '<div style="display:flex;justify-content: center;align-items: center;height: 
				100%;font-size: 20px;color: red;"><p>'.INDEX_R25.'</p>';
		echo '<button type="button" class="button_re_home" style="margin-top : 10px; max-width: 144px !important;"
			onclick="location.href = \''.$_SESSION['base_url'].'\';">'.INDEX_R27.'</button></div>';
		include('./theme/footer.php');
	}else if ($_GET['func'] == "request_pass" && isset($_POST['cc_email'])) {
		if ($_POST['cc_email'] != '' && preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', $_POST['cc_email'])) {
			$query= $pdo->prepare("SELECT token FROM user WHERE email=:email");
			$query->execute(array('email' => $_POST['cc_email']));
			if ($val = $query->fetch()) {
				$link_mail = $_SESSION['base_url']."requests.php?func=reset_pass_confirm&email=".$_POST['cc_email']."&token=".$val[0];
				send_mail ($_POST['cc_email'], INDEX_R29, INDEX_R30 .$link_mail, INDEX_R30."<br/><b>".$link_mail."</b>");
				echo INDEX_R18;
			}else{
				http_response_code(201);
				echo INDEX_R28;
			}
		}else{
			http_response_code(201);
			echo INDEX_R20;
		}
	}else if ($_GET['func'] == "reset_pass_confirm" && isset($_GET['email']) && isset($_GET['token']) && !isset($_SESSION['start'])) {
		include('./theme/header.php');
		if ($_GET['email'] != '' && preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', $_GET['email']) && $_GET['token'] != '')
		{
			$query = $pdo->prepare("SELECT id FROM `user` WHERE `email` = :email AND `token` = :token");
			$query->execute(array('email' => $_GET['email'], 'token' => $_GET['token']));
			if ($val = $query->fetch())
			{
				if (isset($_POST['new_pass']) && isset($_POST['new_pass_v1']) &&
					$_POST['new_pass'] != '' && $_POST['new_pass_v1'] != '') {
					if ($_POST['new_pass'] == $_POST['new_pass_v1'] && strlen($_POST['new_pass']) > 5) {
						$new_pass = hash("whirlpool", $_POST['new_pass']);
						$token = uniqid(rand(), true);
						$query = $pdo->prepare("UPDATE `user` SET `password`= :password, `token`= :token WHERE `id` = :id");
						$query->execute(array('password' => $new_pass,'id' => $val[0],'token' => $token));
						echo '<div style="display:flex;justify-content: center;align-items: center;height: 
							100%;font-size: 20px;color: green; flex-flow:column;"><p>'.INDEX_R37.'<br/></p>
							<button type="button" class="button_re_home" style="margin-top : 10px; max-width: 144px !important;"
								onclick="location.href = \''.$_SESSION['base_url'].'\';">'.INDEX_R27.'</button>';
					} else {
						if (strlen($_POST['new_pass']) > 5)
							print('<section class="connection_session" style="height: 100%;">
								<div id="request_link_pass">
									<p id="warning_info_pass" class="warning_error" style="color: red;">'.INDEX_R35.'</p>');
						else
							print('<section class="connection_session" style="height: 100%;">
								<div id="request_link_pass">
									<p id="warning_info_pass" class="warning_error" style="color: red;">'.INDEX_R17.'</p>');
						print('<form method="post" action="requests.php?func=reset_pass_confirm&email='.$_GET['email'].'&token='.$_GET['token'].'">
					 				<h3>'.INDEX_R31.'</h3>
					 				<input type="password" name="new_pass" placeholder="'.INDEX_R32.'">
					 				<input type="password" name="new_pass_v1" placeholder="'.INDEX_R33.'">
					 				<button type="submit">'.INDEX_R34.'</button>
					 				<a id="return_to_connect" onclick="location.href = \''.$_SESSION['base_url'].'\';"
					 					style="cursor: pointer;">'.INDEX_R16.'</a></form></div></section>');
					}
				} else
					print('<section class="connection_session" style="height: 100%;">
							<div id="request_link_pass">
					 			<form method="post" action="requests.php?func=reset_pass_confirm&email='.$_GET['email'].'&token='.$_GET['token'].'">
					 				<h3>'.INDEX_R31.'</h3>
					 				<input type="password" name="new_pass" placeholder="'.INDEX_R32.'">
					 				<input type="password" name="new_pass_v1" placeholder="'.INDEX_R33.'">
					 				<button type="submit">'.INDEX_R34.'</button>
					 				<a id="return_to_connect" onclick="location.href = \''.$_SESSION['base_url'].'\';"
					 					style="cursor: pointer;">'.INDEX_R16.'</a></form></div></section>');
			} else
				echo '<div style="display:flex;justify-content: center;align-items: center;height: 
				100%;font-size: 20px;color: red;"><p>'.INDEX_R38.'</p>
				<button type="button" class="button_re_home" style="margin-top : 10px; max-width: 144px !important;"
					onclick="location.href = \''.$_SESSION['base_url'].'\';">'.INDEX_R27.'</button></div>';
		} else
			echo '<div style="display:flex;justify-content: center;align-items: center;height: 
			100%;font-size: 20px;color: red;"><p>'.INDEX_R38.'</p>
			<button type="button" class="button_re_home" style="margin-top : 10px; max-width: 144px !important;"
				onclick="location.href = \''.$_SESSION['base_url'].'\';">'.INDEX_R27.'</button></div>';
		include('./theme/footer.php');
	} else if ($_GET['func'] == "login" && isset($_POST['cc_email']) && isset($_POST['cc_password'])) {
		if ($_POST['cc_email'] != '' && $_POST['cc_password'] != '') {
			if (!preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', $_POST['cc_email'])) {
				http_response_code(201);
				echo INDEX_R20;
			} else {
				$hash_pass = hash("whirlpool", $_POST['cc_password']);
				$query= $pdo->prepare("SELECT id, default_lang, active FROM user WHERE email=:email AND password=:password");
				$query->execute(array('email' => $_POST['cc_email'],'password' => $hash_pass));
				if ($val = $query->fetch()){
					if ($val['active'] == 1){
						$_SESSION['start'] = 1;
						$_SESSION['user_id'] = $val[0];
						$_SESSION['lang'] = $val[1];
						http_response_code(202);
						echo "news.php";
					} else {
						http_response_code(201);
						echo INDEX_R40;
					}
				} else {
					http_response_code(201);
					echo INDEX_R39;
				}
			}
		} else {
			http_response_code(201);
			echo INDEX_R19;
		}
	} else if ($_GET['func'] == "edit_profil" && isset($_SESSION['start']) && isset($_POST['cc_email']) && isset($_SESSION['user_id']) &&
		isset($_POST['cc_name']) && isset($_POST['cc_username']) && isset($_POST['cc_oldpassword']) 
		&& isset($_POST['cc_notif']) && isset($_POST['cc_newpassword'])) {
		if ($_POST['cc_username'] != '' && $_POST['cc_email'] != '' && $_POST['cc_name'] != '' && isset($_POST['cc_lang']) && $_POST['cc_lang'] != '')
		{
			if (preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', $_POST['cc_email'])) {
				$query= $pdo->prepare("SELECT * FROM user WHERE id=:id");
				$query->execute(array('id' => $_SESSION['user_id']));
				$val = $query->fetch();
				if ($_POST['cc_lang'] != $val['default_lang'])
					change_language($_POST['cc_lang']);
				if ($_POST['cc_oldpassword'] != '' && $_POST['cc_newpassword'] != '') {
					if (strlen($_POST['cc_newpassword']) > 6) {
						$hash_pass = hash("whirlpool", $_POST['cc_oldpassword']);
						if ($val['password'] == $hash_pass) {
							$hash_pass = hash("whirlpool", $_POST['cc_newpassword']);
							$query= $pdo->prepare("UPDATE `user` SET `name`=:name,`username`=:username,`email`=:email,
													`password`=:password,`default_lang`=:lang, `notification`=:notif WHERE id=:id");
							$query->execute(array('name' =>$_POST['cc_name'], 'username' =>$_POST['cc_username'],
													'email' =>$_POST['cc_email'], 'password' =>$hash_pass, 
													'lang' => $_POST['cc_lang'], 'id' => $_SESSION['user_id'], 'notif' => $_POST['cc_notif']));
							echo EDITPROFIL_R4;
						} else {
							http_response_code(201);
							echo EDITPROFIL_R2;
						}
					} else {
						http_response_code(201);
						echo INDEX_R17;
					}
				} else {
					$query= $pdo->prepare("UPDATE `user` SET `name`=:name,`username`=:username,`email`=:email,
											`default_lang`=:lang, `notification`=:notif WHERE id=:id");
					$query->execute(array('name' =>$_POST['cc_name'], 'username' =>$_POST['cc_username'],
											'email' =>$_POST['cc_email'], 'lang' => $_POST['cc_lang'], 'id' => $_SESSION['user_id']
											, 'notif' => $_POST['cc_notif']));
					echo EDITPROFIL_R4;
				}
			} else {
				http_response_code(201);
				echo INDEX_R20;
			}
		} else {
			http_response_code(201);
			echo EDITPROFIL_R1;
		}
	} else if ($_GET['func'] == "edit_profil_img" && isset($_SESSION['start']) && isset($_FILES['cc_img'])) {
   		$info = pathinfo($_FILES['cc_img']['name']);
		$ext = $info['extension'];
		$newname = uniqid().$ext; 
		$target = './theme/img/profil_pictures/'.$newname;
		$target_v1 = 'theme/img/profil_pictures/'.$newname;
		$img_link = "./".$target_v1;
		if (move_uploaded_file($_FILES['cc_img']['tmp_name'], $target)) {
			$query= $pdo->prepare("UPDATE `user` SET `img_profil`=:img WHERE id=:id");
			$query->execute(array('img' =>$img_link, 'id' => $_SESSION['user_id']));
			echo EDITPROFIL_R6;
		} else {
			http_response_code(201);
			echo EDITPROFIL_R7;
		}
	} else if ($_GET['func'] == "delete_user" && isset($_SESSION['start']) && isset($_SESSION['user_id']) &&
				isset($_GET['token']) && $_GET['token'] != '') {
		$query= $pdo->prepare("SELECT name FROM user WHERE id=:id AND token=:token");
		$query->execute(array('id' => $_SESSION['user_id'], 'token' => $_GET['token']));
		if ($val = $query->fetch())
		{
			$query= $pdo->prepare("UPDATE `user` SET `active`= 0 WHERE id=:id");
			$query->execute(array('id' => $_SESSION['user_id']));
			unset($_SESSION['start']);
          	unset($_SESSION['user_id']);
          	unset($_SESSION['lang']);
          	unset($_SESSION['pre_post']);
          	session_destroy();
		} else {
			http_response_code(201);
			echo EDITPROFIL_R9;
		}
	} else if ($_GET['func'] == "create_post_img" && isset($_SESSION['start']) && isset($_GET['type'])){
		if (isset($_POST['background']) && (isset($_FILES['file1']) || isset($_POST['file2'])))
		{
			$name_file_upload = uniqid();
			if (($background = explode('+', $_POST['background'])) && count($background) != 8) {
				echo EDITPROFIL_R9;
				http_response_code(201);
			} else {
				$ok = 0;
				if ($_GET['type'] == 1) {
					if (imagepng(create_from_img($_FILES['file1']), "./theme/img/tmp/".$name_file_upload.".png")) {
						$ok = 1;
						resize($background[1], "./theme/img/tmp/".$name_file_upload, "./theme/img/tmp/".$name_file_upload.".png");
					} else {
						echo POST_R1;
						http_response_code(201);
					}
				} else if ($_GET['type'] == 2) {
					$photo = $_POST['file2'];
					list($type, $data) = explode(';', $photo);
					list(, $data) = explode(',', $data);
					$data = str_replace(' ', '+', $data);
					$data = base64_decode($data);
					if (file_put_contents("./theme/img/tmp/".$name_file_upload.".png", $data)) {
						resize($background[1], "./theme/img/tmp/".$name_file_upload, "./theme/img/tmp/".$name_file_upload.".png");
						$ok = 1;
					} else {
						echo POST_R1;
						http_response_code(201);
					}
				} else {
					echo EDITPROFIL_R9;
					http_response_code(201);
				}
				if ($ok) {
					$source = imagecreatefrompng("./theme/img/tmp/".$name_file_upload.".png");
					list($width, $height, $type, $attr) = getimagesize("./theme/img/tmp/".$name_file_upload.".png");
					imagealphablending($source, true);
					imagesavealpha($source, true);
					$destination = imagecreatefrompng("./theme/img/croquis_pictures/".$background[0].".png");
					imagecopy($destination, $source, $background[2], $background[3], $background[4], $background[5],
						$background[6], (($background[7] > $height) ? $height : $background[7]));
					$name_file_final = uniqid();
					if (imagepng($destination, "./theme/img/post_pictures/tmp/".$name_file_final.".png")) {
						unlink("./theme/img/tmp/".$name_file_upload.".png");
						$url_final = $_SESSION['base_url'].'theme/img/post_pictures/tmp/'.$name_file_final;
						$url_final_thumb = $_SESSION['base_url'].'theme/img/post_pictures/tmp/'.$name_file_final.'-thumb.png';
						resize(200, './theme/img/post_pictures/tmp/'.$name_file_final.'-thumb',
							'./theme/img/post_pictures/tmp/'.$name_file_final.'.png');
						if (!isset($_SESSION['pre_post']))
							$_SESSION['pre_post'] = '';
						if ($_SESSION['pre_post'] != '')
							$_SESSION['pre_post'] = $_SESSION['pre_post'].",".$url_final;
						else
							$_SESSION['pre_post'] = $url_final;
						echo $url_final;
					} else {
						echo POST_R1;
						http_response_code(201);
					}
				}
			}
		}
	} else if ($_GET['func'] == "publish_post" && isset($_SESSION['start']) && isset($_POST['image_publish']) && isset($_POST['comment_post'])) {
		$link_img = str_replace($_SESSION['base_url'], './', $_POST['image_publish']);
		if ($_POST['image_publish'] != '' && file_exists($link_img)) {
		 	$source = imagecreatefrompng($link_img);
		 	$name_file_final = uniqid();
		 	$final_url = "./theme/img/post_pictures/".$name_file_final."_".$_SESSION['user_id'].".png";
		 	if (imagepng($source, $final_url)) {
		 		$tab_img = explode(',', $_SESSION['pre_post']);
		 		$url_whitout_ext = str_replace('.png', '', $_POST['image_publish']);
		 		$url_thumb = str_replace('.png', '-thumb.png', $link_img);
		 		unlink($url_thumb);
		 		unlink($link_img);
		 		$tab_img = array_diff($tab_img, [$url_whitout_ext]);
		 		$_SESSION['pre_post'] = implode(",", $tab_img);
		 		$query= $pdo->prepare("INSERT INTO `post`(`id`, `id_user`, `img`, `comment`, `date`) VALUES (0,:id_user,:img,:comment,now())");
				$query->execute(array('id_user' => $_SESSION['user_id'], 'img' => $final_url, 'comment' => $_POST['comment_post']));
		 		echo POST_R3;
		 		http_response_code(203);
		 	} else {
		 		http_response_code(206);
				echo POST_R1;
		 	}
		} else {
			http_response_code(206);
			echo POST_R2;
		}
	} else if ($_GET['func'] == "get_post" && isset($_GET['id_last_post']) && $_GET['id_last_post'] != '') {
		$query= $pdo->prepare("SELECT * FROM `post` ORDER BY date DESC LIMIT ".$_GET['id_last_post'].", 5");
		$query->execute();
		$post_R = $query->fetchAll();
		$post_R_count = $query->rowCount();
		if ($post_R_count > 0) {
			$i = 0;
			foreach ($post_R as $post_by_post) {
			  $img_post = $post_by_post['img'];
				if (file_exists($img_post)) {
				    $comment_post = $post_by_post['comment'];
				    $query = $pdo->prepare("SELECT username, active, img_profil FROM `user` WHERE id=:id");
				    $query->execute(array('id' =>$post_by_post['id_user']));
				    $user_R = $query->fetch();

				    $username_poster = $user_R['username'];
				    $active = $user_R['active'];
				    $img_user = $user_R['img_profil'];
				    if ($active) {
					    print('	  <header>
					                <div id="profil_picture_r1">
					                  <img id="real_img_profil" src="'.$img_user.'" alt="Profil Picture">
					                </div>
					                <span id="name_customers">'.$username_poster.'</span>
					              </header>
					              <div class="post_img">
					                <img src="'.$img_post.'" alt="'.$comment_post.'">
					              </div>
					              <div class="post_like">');

					    if (isset($_SESSION['start'])) {
					    	// On verifie si le user actuel a liker
					    	$query = $pdo->prepare("SELECT COUNT(`id`) FROM likes WHERE `id_post` = :id AND `id_user` = :iduser");
					    	$query->execute(array('id' =>$post_by_post['id'], 'iduser' => $_SESSION['user_id']));
					    	$val_likeur_ok = $query->fetchColumn();
					        print('<div class="post_like_r1">
					                  <a class="post_like_a_like" onclick="javascript:like_post(this,'.$post_by_post['id'].')" title="'.NEWS_R13.'"
					                  style="cursor:pointer;'.($val_likeur_ok ? "background-position: -182px -336px;" : "").'"></a>
					                  <a class="post_like_a_comment" style="cursor:pointer;" onclick="javascript: 
					                  	this.parentNode.parentNode.parentNode.querySelector(\'.add_comment_post_area\').select();" title="'.NEWS_R14.'"></a>
					                  	'.($post_by_post['id_user'] == $_SESSION['user_id'] ? "
					                  	<a class=\"post_delete_link\" onclick=\"javascript:delete_post(this,
					                  		".$post_by_post['id'].")\" title=\"".NEWS_R16."\" style=\"cursor:pointer;\"></a>" : "" ).'
					               </div>');
					    } else
					    	print('<div class="post_like_r1"></div>');
					    print('     <div class="post_like_r2">');

					    // On selectionne le nom des 3 derniers likeur
					    $query = $pdo->prepare("SELECT l.id_user, u.username, u.id FROM likes l INNER JOIN user u ON l.id_user = u.id
					                  WHERE l.id_post=:id ORDER BY l.date DESC LIMIT 3");
					    $query->execute(array('id' =>$post_by_post['id']));
					    $like_username = $query->fetchAll();
					    $likeur_name = '';
					    foreach ($like_username as $key) {
					    	if (isset($_SESSION['start']) && ($key['id'] == $_SESSION['user_id']))
					      		$likeur_name = $likeur_name.", ".NEWS_R15;
					      	else
					      		$likeur_name = $likeur_name.", ".$key['username'];
					    }
					    $likeur_name = substr($likeur_name, 2);

					    // On compte le nombre de likes
					    $query = $pdo->prepare("SELECT COUNT(`id`) FROM likes WHERE `id_post` = :id");
					    $query->execute(array('id' =>$post_by_post['id']));
					    $number_likes = $query->fetchColumn();

					    if ($number_likes > 3) {
					    	$likeur_name = $likeur_name.", ..";
					    	$change_text = '<span id="like_customers_post"><b>'.$likeur_name.'</b> '.$number_likes.' '.NEWS_R1.'</span>';
					    } else if ($number_likes <= 3 && $number_likes != 0) {
					    	$change_text = '<span id="like_customers_post"><b>'.$likeur_name.'</b>'.NEWS_R17.'</span>';
					    } else if ($number_likes == 0) {
					    	$change_text = '<span id="like_customers_post">'.$number_likes.' '.NEWS_R1.'</span>';
					    }

					    // On compte le nombre de commantaires
					    $query = $pdo->prepare("SELECT COUNT(`id`) FROM comment WHERE `id_post` = :id");
					    $query->execute(array('id' =>$post_by_post['id']));
					    $number_comment = $query->fetchColumn();
					    
					    print($change_text.($comment_post != '' ? '<span id="bio_customers_post"><b>'.$username_poster.'</b> '.$comment_post.'</span>' : "").'
					              </div>
					              </div>
					              <div class="post_comment">');
					    if ($number_comment > 0) {
					      print('     <div class="post_comment_r0">
					                  	<span id="comment_post_onebyone_title">'.$number_comment.' '.NEWS_R2.'</span>
					                  </div>
					                <div class="post_comment_r1">');
					      // On selectionne le nom et le commentaire des commentateur
					      $query = $pdo->prepare("SELECT l.id_user, l.comment, l.id, l.date, u.username FROM comment l INNER JOIN user u
					                    ON l.id_user = u.id WHERE l.id_post=:id ORDER BY l.date");
					      $query->execute(array('id' =>$post_by_post['id']));
					      $comment_username = $query->fetchAll();
					      foreach ($comment_username as $comment)
					        print('     <span id="comment_post_onebyone"><b>'.$comment['username'].'</b> '.$comment['comment'].($comment['id_user'] == $_SESSION['user_id'] ? "<a class=\"post_delete_comment\" onclick=\"javascript:delete_comment(this,".$comment['id'].",".$post_by_post['id'].")\" title=\"".NEWS_R19."\"></a>" : "").'</span>');
					    } else 
					      print('	<div class="post_comment_r0">
					                  	<span id="comment_post_onebyone_title"></span>
					                </div>
					      		     <div class="post_comment_r1">');
					    print('   </div>
					    		  <span id="comment_post_onebyone_time">'.getRelativeTime($post_by_post['date']).'</span>');
					    if (isset($_SESSION['start']))
					       print('<div class="post_comment_r2">
					                <form class="add_comment_post" method="post" action="requests.php?func=add_comment&id_post='.$post_by_post['id'].'">
					                  <textarea aria-label="'.NEWS_R11.'" class="add_comment_post_area" name="comment"
					                      placeholder="'.NEWS_R11.'" style="height: 18px;" onkeyup="javascript:add_comment(this,'.$post_by_post['id'].')"></textarea>
					              	</form>
					              </div>');
					   	else
					   		print('<div class="post_comment_r2" style="text-align:center;"><a href="'.$_SESSION['base_url'].'"
					   					style="color:#000;">'.NEWS_R12.'</a></div>');
					    print('  </div>');
					    if ($i != $post_R_count - 1)
					    	print('˘');
						$i++;
					} // end active
			  	} // end file_exist
			} // end foreach
		} else {
			http_response_code(201);
		}
	} else if ($_GET['func'] == "like_post" && isset($_GET['id_post']) && $_GET['id_post'] != '' && isset($_SESSION['start'])) {
		$query = $pdo->prepare("SELECT COUNT(`id`) FROM likes WHERE `id_post` = :id AND `id_user` = :iduser");
    	$query->execute(array('id' =>$_GET['id_post'], 'iduser' => $_SESSION['user_id']));
    	$val_likeur_ok = $query->fetchColumn();
    	if ($val_likeur_ok) {
    		$query = $pdo->prepare("DELETE FROM `likes` WHERE `id_post` = :id AND `id_user` = :iduser");
    		$query->execute(array('id' =>$_GET['id_post'], 'iduser' => $_SESSION['user_id']));
    		$end_send = "-208px -336px";
    	} else {
    		$query = $pdo->prepare("INSERT INTO `likes` (`id`, `id_post`, `id_user`, `date`) VALUES (0,:id_post,:id_user,now())");
    		$query->execute(array('id_post' =>$_GET['id_post'], 'id_user' => $_SESSION['user_id']));
    		$end_send = "-182px -336px";
    	}
    	// On selectionne le nom des 3 derniers likeur
	    $query = $pdo->prepare("SELECT l.id_user, u.username, u.id FROM likes l INNER JOIN user u ON l.id_user = u.id
	                  WHERE l.id_post=:id ORDER BY l.date DESC LIMIT 3");
	    $query->execute(array('id' =>$_GET['id_post']));
	    $like_username = $query->fetchAll();
	    $likeur_name = '';
	    foreach ($like_username as $key) {
	    	if ($key['id'] == $_SESSION['user_id'])
	    		$likeur_name = $likeur_name.", ".NEWS_R15;
	    	else
	      		$likeur_name = $likeur_name.", ".$key['username'];
	    }
	    $likeur_name = substr($likeur_name, 2);
	    // On compte le nombre de likes
	    $query = $pdo->prepare("SELECT COUNT(`id`) FROM likes WHERE `id_post` = :id");
	    $query->execute(array('id' =>$_GET['id_post']));
	    $number_likes = $query->fetchColumn();

	    if ($number_likes > 3) {
	    	$likeur_name = $likeur_name.", ..";
	    	$change_text = '<span id="like_customers_post"><b>'.$likeur_name.'</b> '.$number_likes.' '.NEWS_R1.'</span>';
	    } else if ($number_likes <= 3 && $number_likes != 0)
	    	$change_text = '<span id="like_customers_post"><b>'.$likeur_name.'</b>'.NEWS_R17.'</span>';
	    else if ($number_likes == 0)
	    	$change_text = '<span id="like_customers_post">'.$number_likes.' '.NEWS_R1.'</span>';
	  	$end_send = $end_send."+".$change_text;
	  	echo $end_send;
	} else if ($_GET['func'] == "delete_post" && isset($_GET['id_post']) && $_GET['id_post'] != '' && isset($_SESSION['start'])) {
		$query = $pdo->prepare("DELETE FROM post WHERE `id` = :id AND `id_user` = :user_id");
	    $query->execute(array('id' => $_GET['id_post'], 'user_id' => $_SESSION['user_id']));
	    $number_likes = $query->rowCount();
	    if (!$number_likes)
	    	http_response_code(201);
	} else if ($_GET['func'] == "delete_comment" && isset($_GET['id_comment']) && $_GET['id_comment'] != '' && isset($_SESSION['start'])) {
		$query = $pdo->prepare("DELETE FROM comment WHERE `id` = :id AND `id_user` = :user_id");
	    $query->execute(array('id' => $_GET['id_comment'], 'user_id' => $_SESSION['user_id']));
	    $number_likes = $query->rowCount();
	    if (!$number_likes)
	    	http_response_code(201);
	    else {
	    	$query1 = $pdo->prepare("SELECT COUNT(`id`) FROM `comment` WHERE `id_post` = :id_post");
			$query1->execute(array('id_post' =>$_GET['id_post']));
			$number_comment = $query1->fetchColumn();
			echo $number_comment." ".NEWS_R2;
	    }
	} else if ($_GET['func'] == "add_comment" && isset($_GET['id_post']) && $_GET['id_post'] != '' && isset($_SESSION['start']) && isset($_POST['comment'])) {
		// on selectionne l'id du user du post et les info du user du post
		$query = $pdo->prepare("SELECT u.name, u.username, u.email, u.notification FROM post p
								INNER JOIN user u ON p.id_user = u.id WHERE p.id = :id");
		$query->execute(array('id' =>$_GET['id_post']));
		$user_R = $query->fetch();

		$query2 = $pdo->prepare("SELECT username FROM user WHERE id = :id");
		$query2->execute(array('id' => $_SESSION['user_id']));
		$user_V = $query2->fetch();

		if ($query->rowCount()) {
			$query = $pdo->prepare("INSERT INTO `comment`(`id`, `id_post`, `id_user`, `comment`, `date`) VALUES (0,:id_post,:id_user,:comment,now())");
			$query->execute(array('id_post' =>$_GET['id_post'], 'id_user' => $_SESSION['user_id'], 'comment' => $_POST['comment']));
			$id_comment = $pdo->lastInsertId();
			$query1 = $pdo->prepare("SELECT COUNT(`id`) FROM `comment` WHERE `id_post` = :id_post");
			$query1->execute(array('id_post' =>$_GET['id_post']));
			$number_comment = $query1->fetchColumn();
			echo "<b>".$user_V['username']."</b> ".$_POST['comment']."<a class=\"post_delete_comment\" onclick=\"javascript:delete_comment(this,".$id_comment.",".$_GET['id_post'].")\" title=\"".NEWS_R19."\"></a>˘".$number_comment." ".NEWS_R2;
			if ($user_R['notification'] == 1)
				send_mail ($user_R['email'], NEWS_R20, NEWS_R21.$user_V['username'].NEWS_R22.$_POST['comment'], NEWS_R21.$user_V['username'].NEWS_R22.$_POST['comment']);
		} else
			http_response_code(201);
	} else if ($_GET['func'] == "logout" && isset($_SESSION['start'])) {
      	session_destroy();
	} // Travail ICI
}
?>