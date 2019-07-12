<?php
  if(session_status() == PHP_SESSION_NONE){
    session_start();
  }

  if(isset($_SESSION['modify-data'])){
    unset($_SESSION['modify-data']);
  }

  error_reporting(0);
  $rootf = RootFolder();
  $root = $_SERVER['DOCUMENT_ROOT'].$rootf;
  $functions = true;
  require_once $root."files/php-lib/page-content.php";
  require_once $root."files/php-lib/database.php";
  
  $menu = MenuLinks(GetUserData());
  $path = basename(getcwd());
  if(IsRootFolder()){
    $path = "";
  }

  if(! isset($title) || $title == false){
    $title = CurrentLinkName($path, $menu);
  }
  
  setcookie("ci", 1, time() + (86400 * 150), "/");

  CheckUser();

?>
<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link rel="Shortcut icon" href="<?php echo $rootf ?>files/Logo.png">
    <link rel="stylesheet" href="<?php echo $rootf ?>files/css/Fontello/css/fontello.css">
    <link rel="stylesheet" href="<?php echo $rootf ?>files/css/select-tag.css">
    <link rel="stylesheet" href="<?php echo $rootf ?>files/css/style.css">
    <?php
      if(file_exists($root."files/css/".$path.".css")){
        echo '<link rel="stylesheet" href="'.$rootf.'files/css/'.$path.'.css">';
      }

      if(isset($css)){
        if(file_exists($root."files/css/".$css.".css")){
          echo '<link rel="stylesheet" href="'.$rootf.'files/css/'.$css.'.css">';
        }
      }
    ?>
    <title><?php
      echo $title;
    ?></title>
    <script src="<?php echo $rootf ?>files/js/jquery-min.js"></script>
    <script src="<?php echo $rootf ?>files/js/mainjs.js"></script>
    <script src="<?php echo $rootf ?>files/js/images.js"></script>
    <?php
      if(file_exists($root."files/js/".$path.".js")){
        echo '<script src="'.$rootf.'files/js/'.$path.'.js"></script>';
      }
    ?>
  </head>

  <body>
    <div id="body" class="scrollable">
      <header id="header">
        <a href="<?php echo $rootf ?>"><img src="<?php echo $rootf ?>files/Logo.png" alt="Logo"></a>
      </header>

      <nav id="menu-container">
        <div id="menu" class="scrollable">
          <div class="nav-buttons-container">
            <button class="menu-toggle-button">
              <span class="icon-menu-1"></span>
            </button>
          </div>
          <div id="nav-links-container"><?php

            for($m = 0; $m < count($menu); $m++){
              echo '<a href="'.$rootf.$menu[$m]['link'].'" class="nav-link nav-color';

              if($menu[$m]['link'] == "account"){
                echo ' icon-user';
              }

              if($menu[$m]['link'] == $path){
                echo ' active';
              }

              echo '">'.$menu[$m]['link-name'].'</a>';
            }

          ?></div>
        </div>
      </nav>

      <main>
        <noscript><?php
          InfoHeader("Wyłączona obsługa JavaScript", -1);
        ?></noscript>
        <?php
          Content();
        ?>
      </main>
    <?php
      if(! isset($_COOKIE['ci'])){
        echo
        '<div id="cookies-info-div" class="scrollable">
          <h1 id="cookies-info-header" class="title">Pliki cookie</h1>
          <div id="cookie-info" class="description">Strona zapisuje na urządzeniu końcowym użytkownika pliki cookie w celu zapewnienia pełnej funkcjonalności. Jeśli nie chcesz aby pliki cookie były zapisywane na Twoim urządzeniu, wyłącz ich obsługę w ustawieniach przeglądarki.</div>
          <button id="cookie-div-close-button"><span class="icon-cancel"></span></button>
        </div>';
      }
    ?>
    </div>
  </body>
</html>
