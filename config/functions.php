<?php

function include_lang($lang) {
	switch ($lang){
	    case "FR":
	        include("theme/lang/fr-lang.php");
	        break;
	    case "EN":
	        include("theme/lang/en-lang.php");
	        break;        
	    default:
	        include("theme/lang/en-lang.php");
	        break;
	}
}

function Get_default_language() {
	$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	switch ($lang){
	    case "fr":
	        include_lang("FR");
	        $_SESSION['lang'] = "FR";
	        break;
	    case "en":
	        include_lang("EN");
	        $_SESSION['lang'] = "EN";
	        break;        
	    default:
	        include_lang("EN");
	        $_SESSION['lang'] = "EN";
	        break;
	}
}

function change_language($lang) {
	switch ($lang){
	    case "FR":
	        include_lang("FR");
	        $_SESSION['lang'] = "FR";
	        break;
	    case "EN":
	        include_lang("EN");
	        $_SESSION['lang'] = "EN";
	        break;        
	    default:
	        include_lang("EN");
	        $_SESSION['lang'] = "EN";
	        break;
	}
}

function send_mail ($mail, $subject, $message, $html_message) {
	if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail))
		$passage_ligne = "\r\n";
	else
		$passage_ligne = "\n";

	$message_txt = $message;
	$message_html = $html_message;
	$boundary = "-----=".md5(rand());
	$sujet = $subject;

	$header = "From: \"Camagru\"<ybitton@42.fr>".$passage_ligne;
	$header.= "Reply-to: \"Camagru\" <ybitton@42.fr>".$passage_ligne;
	$header.= "MIME-Version: 1.0".$passage_ligne;
	$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
	$headers = "Content-Type: text/html; charset=UTF-8";
	
	$message = $passage_ligne."--".$boundary.$passage_ligne;
	$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
	$message.= $passage_ligne.$message_txt.$passage_ligne;
	$message.= $passage_ligne."--".$boundary.$passage_ligne;
	$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
	$message.= $passage_ligne.$message_html.$passage_ligne;
	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
	
	mail($mail,$sujet,$message,$header);
}

function create_from_img($filename) {
	$extension = pathinfo($filename['name'], PATHINFO_EXTENSION);
	switch ($extension) {
	    case 'jpg':
	    case 'jpeg':
	       $image = imagecreatefromjpeg($filename['tmp_name']);
	    break;
	    case 'gif':
	       $image = imagecreatefromgif($filename['tmp_name']);
	    break;
	    case 'png':
	       $image = imagecreatefrompng($filename['tmp_name']);
	    break;
	}
	return $image;
}

function resize($newWidth, $targetFile, $originalFile) {

    $info = getimagesize($originalFile);
    $mime = $info['mime'];

    switch ($mime) {
            case 'image/jpeg':
                    $image_create_func = 'imagecreatefromjpeg';
                    $image_save_func = 'imagejpeg';
                    $new_image_ext = 'jpg';
                    break;

            case 'image/png':
                    $image_create_func = 'imagecreatefrompng';
                    $image_save_func = 'imagepng';
                    $new_image_ext = 'png';
                    break;

            case 'image/gif':
                    $image_create_func = 'imagecreatefromgif';
                    $image_save_func = 'imagegif';
                    $new_image_ext = 'gif';
                    break;

            default: 
                    throw new Exception('Unknown image type.');
    }

    $img = $image_create_func($originalFile);
    list($width, $height) = getimagesize($originalFile);

    $newHeight = ($height / $width) * $newWidth;
    $tmp = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    if (file_exists($targetFile)) {
            unlink($targetFile);
    }
    $image_save_func($tmp, "$targetFile.$new_image_ext");
}

function getRelativeTime($date) {
    $date_a_comparer = new DateTime($date);
    $date_actuelle = new DateTime("now");
	$intervalle = $date_a_comparer->diff($date_actuelle);

	$prefixe = NEWS_R3;
	$ans = $intervalle->format('%y');
	$mois = $intervalle->format('%m');
	$jours = $intervalle->format('%d');
	$heures = $intervalle->format('%h');
	$minutes = $intervalle->format('%i');
	$secondes = $intervalle->format('%s');

	if ($ans != 0) {
		$relative_date = $ans . NEWS_R4 . (($ans > 1) ? 's' : '');
		if ($mois >= 6) 
			$relative_date .= NEWS_R8;
	} elseif ($mois != 0) {
		$relative_date = $mois . NEWS_R5;
		if ($jours >= 15)
			$relative_date .= NEWS_R8;
	} elseif ($jours != 0) {
		$relative_date = $jours . NEWS_R6 . (($jours > 1) ? 's' : '');
	} elseif ($heures != 0) {
		$relative_date = $heures . NEWS_R7 . (($heures > 1) ? 's' : '');
	} elseif ($minutes != 0) {
		$relative_date = $minutes . NEWS_R9 . (($minutes > 1) ? 's' : '');
	} else {
		$relative_date = NEWS_R10;
	} 
	if ($_SESSION['lang'] == "FR")
		$relative_date = $prefixe . $relative_date;
	else
		$relative_date = $relative_date . $prefixe;
	return $relative_date;
}

?>
