<?php

  session_start();
  
  $title = "Edytuj obiekt";
  $functions = true;
  require_once "../files/html-content/modify-object.php";

  if(CheckUser() && isset($_GET['type']) && isset($_GET['id'])) {
    
    function Content() {
      ModifyObjectTable(htmlentities($_GET['id'], ENT_QUOTES, "UTF-8"));
    }
    
    require_once "../files/html-content/page.php";
    
  }else{
    header("location: ../index.php");
  }
?>