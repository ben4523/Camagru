<?php
print('
  <!doctype html>
  <html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Camagru Projet</title>
    <meta name="description" content="42 PHP Project">
    <meta name="author" content="ybitton">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache">
    <script>(function(e,t,n){var r=e.querySelectorAll("html")[0];r.className=r.className.replace(/(^|\s)no-js(\s|$)/,"$1js$2")})(document,window,0);</script>
    <link rel="stylesheet" type="text/css" href="theme/css/styles.css">
    <link rel="stylesheet" type="text/css" href="theme/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="theme/css/component.css" />
    <link rel="icon" href="theme/logo.ico" />
    <script src="theme/global.js"></script>
  </head>
  <body>
');
if (isset($_SESSION['start']))
print('
  <header id="header" class="clear">
    <div class="header_r1">
      <div class="logo">
        <div class="logo_r1">
          <a class="logo_img" href="news.php"></a>
        </div>
      </div>
      <div class="customers_details">
        <div class="customers_details_r1">
          <div class="post_customers">
            <a href="post.php" class="post_customers_a customers_details_r2">Profil</a>
          </div>
          <div class="profil_customers">
            <a href="edit_profil.php" class="profil_customers_a customers_details_r2">Profil</a>
          </div>
          <div class="logout_customers">
            <a class="logout_customers_a customers_details_r2" onclick="javascript:logout()">Déconnection</a>
          </div>
        </div>
      </div>
    </div>
  </header>
');
?>
