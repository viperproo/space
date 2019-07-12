<?php
  session_start();
    
  $functions = true;
  require_once "php-lib/database.php";
  require_once "php-lib/page-files.php";

  if(CheckUser() && isset($_GET['id']) && isset($_GET['type'])){

    $connect = Connect();

    if($connect){

      $id = htmlentities($_GET['id'], ENT_QUOTES, "UTF-8");
      $table = htmlentities($_GET['type'], ENT_QUOTES, "UTF-8");

      if(CheckTable($connect, $table)){

        $query = mysqli_query($connect, "DELETE FROM `$table` WHERE `id` = $id");

        if($query){
          $_SESSION['delete-object-error'] = false;
          $query = mysqli_query($connect, "ALTER TABLE `$table` AUTO_INCREMENT = 1");
          
          DeleteFolder("../files/photos/$table/$id");
          
          header("location: ../$table/");
          exit();
        }else{
          $_SESSION['delete-object-error'] = "Nie można usunąć obiektu ponieważ inny obiekt odwołuje się do niego lub wystąpił błąd";
        }

      }else{
        $_SESSION['delete-object-error'] = "Obiekt nie istnieje";
      }

    }else{
      $_SESSION['delete-object-error'] = "Wystąpił błąd. Obiekt nie został usunięty";
    }
    
    header("location: ../$table/?id=$id");
    exit();
    
  }
  header("location: ../");
  exit();
?>