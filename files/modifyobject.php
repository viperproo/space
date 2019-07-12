<?php
  session_start();
  $location = "../";
  $functions = true;
  require_once "php-lib/database.php";
  require_once "php-lib/page-files.php";

  if(CheckUser() && isset($_POST['submit-btn']) && isset($_SESSION['modify-data'])){
    $table = $_SESSION['modify-data']['table'];
    $connect = Connect();
    $fields = mysqli_query($connect, "SHOW COLUMNS IN $table");
    $path = "../files/photos/$table/";
    $upload = false;
    
    if($fields){
      if(! isset($_SESSION['modify-data']['id'])){
        $location = "../add/?type=$table";
        $values = "NULL";
        $_SESSION['values'] = array();

        for($v = 0; $v < mysqli_num_rows($fields) - 1; $v++){

          if(isset($_POST['col-'.$v])){
            $data = $_POST['col-'.$v];
            $inputVal = htmlentities($data, ENT_QUOTES, "UTF-8");
            $val = "'".$inputVal."'";
            if($data == ""){
              $val = "NULL";
            }
          }else{
            $inputVal = NULL;
            $val = "NULL";
          }

          $values .= ", $val";
          array_push($_SESSION['values'], $inputVal);

        }

        $insert = mysqli_query($connect, "INSERT INTO `$table` VALUES ($values)");

        if($insert){
          $new_id_query = mysqli_query($connect, "SELECT MAX(`id`) FROM `$table`");

          if($new_id_query){
            $id = mysqli_fetch_row($new_id_query)[0];
            $path .= "$id";
            if(! file_exists($path)){
              mkdir($path);
            }
            $upload = true;
            $location = "../$table/?id=$id";
          }else{
            $_SESSION['insert-error'] = "Wystąpił błąd serwera";
            $location = "../$table/";
          }

        }else{
          $_SESSION['modify-error'] = "Nie udało się dodać obiektu. Wartość któregoś pola jest nieprawidłowa lub wystąpił błąd serwera.";
        }

      }else{
        $i = 0;
        $s = 1;
        $id = $_SESSION['modify-data']['id'];
        $_SESSION['validate-data'] = array();
        $path .= "$id";
        $location = "../edit/?type=$table&id=$id";
        $upload = true;

        DeletePhotos($path);

        while($field_name = mysqli_fetch_assoc($fields)){

          $field = $field_name['Field'];

          if($field_name['Key'] != 'PRI'){

            $push = false;

            if(isset($_POST['col-'.$i])){
              if($_POST['col-'.$i] == ""){
                $data = "NULL";
              }else{
                $data = "'".htmlentities($_POST['col-'.$i], ENT_QUOTES, "UTF-8")."'";
              }
            }else{
              $data = "NULL";
            }

            $update = mysqli_query($connect, "UPDATE `$table` SET `$field` = $data WHERE `id` = $id");

            if($update){
              $s++;
              $push = true;
            }else{
              $push = false;
            }

            array_push($_SESSION['validate-data'], $push);
            $i++;

          }

        }

        if($s == mysqli_num_rows($fields)){
          unset($_SESSION['validate-data']);
          $_SESSION['modify-error'] = false;
          $location = "../$table/?id=$id";
        }else{
          $_SESSION['modify-error'] = "Nie udało się zapisać wszystkich pól";
        }

      }
      
      if($upload){
        $file = "";
        if(isset($_POST['main-photo'])){
          $file = $_POST['main-photo'];
        }
        ModifyObjectPhotos($file, $path);
        if(isset($_SESSION['file-error'])){
          $location = "../edit/?type=$table&id=$id";
        }
      }
    }else{
      $_SESSION['modify-error'] = "Wystąpił błąd serwera";
    }
    mysqli_close($connect);
  }

  header("location: $location");
  exit();
?>