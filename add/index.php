<?php
  session_start();
  
  $functions = true;
  require_once "../files/html-content/modify-object.php";

  if(CheckUser() && isset($_GET['type'])){
    $title = "Dodaj obiekt";
    $css = "edit";
    
    function Content() {
      ModifyObjectTable();
    }
    
    require_once "../files/html-content/page.php";
    
  }else{
    header("location: ../");
  }
?>