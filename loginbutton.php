<?php
  if(isset($_GET['button']) && ! $_GET['button']){
    $value = 0;
  }else{
    $value = 1;
  }

  setcookie("lb", $value, time() + (86400 * 150), "/");

  header("location: index.php");
?>