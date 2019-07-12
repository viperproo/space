<?php
  require_once "root-folder.php";

  if(! (isset($functions) && $functions)){
    header("location: ".RootFolder());
    exit();
  }

  function MenuLinks($user = false) {
    $menu = array(
      array("link" => "", "link-name" => "Strona główna"),
      array("link" => "gallery", "link-name" => "Galeria"),
      array("link" => "moons", "link-name" => "Księżyce"),
      array("link" => "planets", "link-name" => "Planety"),
      array("link" => "stars", "link-name" => "Gwiazdy"),
      array("link" => "galaxies", "link-name" => "Galaktyki")
    );

    if($user){
      array_push($menu, array("link" => "account", "link-name" => $user['name']));
    }else if(isset($_COOKIE['lb']) && $_COOKIE['lb']){
      array_push($menu, array("link" => "login", "link-name" => "Zaloguj"));
    }

    return $menu;
  }

  function CurrentLinkName($path, $menu) {
    if(IsRootFolder()){
      return $menu[0]['link-name'];
    }
    for($m = 1; $m < count($menu); $m++){
      if($menu[$m]['link'] == $path){
        return $menu[$m]['link-name'];
      }
    }
    return "Kosmos";
  }

  function MinImageDiv($photo_path, $alt) {
    echo 
    '<div class="image-container min-image">
      <img src="'.$photo_path.'" alt="'.$alt.'">
    </div>';
  }

  function InfoHeader($info, $error = 0) {
    echo '<h2 class="info-header';

    if($error < 0){
      echo ' error';
    }else if($error > 0){
      echo ' success';
    }

    echo '">'.$info.'</h2>';
  }

  function InputDiv($title, $input_properties, $one_row = true) {
    echo
    '<div class="input-container-div';
    if($one_row){
      echo ' one-row';
    }
    echo
    '">
      <div class="input-title-div">'.$title.'</div>
      <div class="input-div">
        <input';

        for($x = 0; $x < count($input_properties); $x++){
          echo ' '.$input_properties[$x];
        }

    echo ' placeholder="'.$title.'">';

    echo
      '</div>
    </div>';
  }

  function SessionError($session_name) {
    if(isset($_SESSION[$session_name])){
      if($_SESSION[$session_name] !== false){
        InfoHeader($_SESSION[$session_name], -1);
      }
      unset($_SESSION[$session_name]);
    }
  }

  function SessionSuccess($session_name, $value) {
    if(isset($_SESSION[$session_name])){
      if($_SESSION[$session_name] === false){
        InfoHeader($value, 1);
      }
      unset($_SESSION[$session_name]);
    }
  }
?>