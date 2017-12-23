<?php

// Afficher les erreurs à l'écran
ini_set('display_errors', 1);
// Enregistrer les erreurs dans un fichier de log
ini_set('log_errors', 1);
// Nom du fichier qui enregistre les logs (attention aux droits à l'écriture)
ini_set('error_log', dirname(__file__) . '/log_error_php.txt');
// Afficher les erreurs et les avertissements
error_reporting(e_all);

if (file_exists("./install.php"))
	header("Location: ./install.php");

//On demarre les sessions avec un expire cache
session_cache_expire(15);
session_start();
date_default_timezone_set('Europe/Paris');
include("./config/functions.php");

// On se connecte a la base de donnée
include("./config/database.php");
try {
    $arrExtraParam= array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $arrExtraParam);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
    print('<section class="connection_session" style="height: 100%;">
			<div>
				<p id="warning_info_pass" class="warning_error" style="color: red;">ERREUR Connection BDD !</p>
				<p id="warning_info_pass" class="warning_error" style="color: red;">'.$e->getMessage().'</p>
			</div>
		   </section>');
}

// On inclue les langues dans PHP
if (!isset($_SESSION['lang']))
	Get_default_language();
else
	include_lang($_SESSION['lang']);

// On verifie la connection a la BDD et on vérifie si une session existante
if (!isset($_SESSION['base_url']))
{
	$query = $pdo->prepare("SELECT `value` FROM `config` WHERE `name` = 'base_url'");
	$query->execute();
	$val = $query->fetch();
	$_SESSION['base_url'] = $val[0];
}

$url_page = str_replace($_SERVER['REQUEST_URI'], "", $_SERVER['PHP_SELF']);
$for_request =  strchr($_SERVER['PHP_SELF'],"requests.php");
$for_galery =  strchr($_SERVER['PHP_SELF'],"news.php");
if (!isset($msg) && !isset($_SESSION['start']) && $url_page != "index.php" && $for_request != "requests.php" && $for_galery != "news.php") {
	header('Location: ' .$_SESSION['base_url']);
}
?>