<?php
  session_start();

  $functions = true;
  require_once "php-lib/database.php";

  if(isset($_POST['current-password']) && isset($_POST['new-password']) && isset($_POST['new-password2']) && CheckUser()){
    
    if(count($_COOKIE) > 0){
            
      $new_password = htmlentities($_POST['new-password'], ENT_QUOTES, "UTF-8");
      
      if(strlen($new_password) > 7){
      
        if($new_password == htmlentities($_POST['new-password2'], ENT_QUOTES, "UTF-8")){

          $connect = Connect();

          if($connect){

            $current_password = htmlentities($_POST['current-password'], ENT_QUOTES, "UTF-8");
            $query = mysqli_query($connect, "SELECT `password` FROM users WHERE `id` = ".$_SESSION['user']);

            if($query){

              if(mysqli_num_rows($query) > 0){

                $user = mysqli_fetch_array($query, MYSQLI_ASSOC);

                $pswd_check = password_verify($current_password, $user['password']);

                if($pswd_check){

                  $hash_pswd = password_hash($new_password, PASSWORD_BCRYPT);
                  $update = mysqli_query($connect, "UPDATE `users` SET password = '$hash_pswd' WHERE `id` = ".$_SESSION['user']);

                  if($update){
                    $_SESSION['password-change-error'] = false;
                  }else{
                    $_SESSION['password-change-error'] = "Wystąpił błąd. Nie udało się zmienić hasła";
                  }

                }else{
                  $_SESSION['password-change-error'] = "Nieprawidłowe obecne hasło";
                }

              }else{
                $_SESSION['password-change-error'] = "Użytkownik nie istnieje";
              }

            }else{
              $_SESSION['password-change-error'] = "Wystąpił błąd podczas próby połączenia z serwerem";
            }

          }else{
            $_SESSION['password-change-error'] = "Wystąpił błąd podczas próby połączenia z serwerem";
          }

        }else{
          $_SESSION['password-change-error'] = "Nowe hasła się nie zgadzają";
        }
      
      }else{
        $_SESSION['password-change-error'] = "Nowe hasło musi mieć od 8 do 30 znaków";
      }
      
      header("location: ../account/");
      exit();
      
    }else{
      header("location: ../login/?cookies=false");
      exit();
    }
    
  }else{    
    header("location: ../");
  }
?>