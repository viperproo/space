<?php
  session_start();

  $functions = true;
  require_once "php-lib/database.php";
  $location = "../";

  if(! CheckUser() && isset($_POST['login']) && isset($_POST['password'])){
    $login = htmlentities($_POST['login'], ENT_QUOTES, "UTF-8");
    $location .= "login/";
    
    if(count($_COOKIE) > 0){
    
      $connect = Connect();

      if($connect){
        
        $query = mysqli_query($connect, "SELECT `id`, `login`, `password` FROM `users` WHERE `login` = '$login'");
        
        if($query){
          
          if(mysqli_num_rows($query) > 0){
            
            $user = mysqli_fetch_assoc($query);
            
            if(password_verify($_POST['password'], $user['password'])){
              $insert = mysqli_query($connect, "INSERT INTO `logins` VALUES (NULL, ".$user['id'].", '".$_SERVER['REMOTE_ADDR']."', now())");
              $bans = Bans($connect, $user['id']);
              if($bans == 1){
                mysqli_close($connect);
                $_SESSION['user'] = $user['id'];
                header("location: ../");
                exit();
              }else if($bans == 0){
                $_SESSION['login-error'] = "Twoje konto zostało zablokowane";
              }else{
                $_SESSION['login-error'] = "Wystąpił błąd serwera";
              }
            }else{
              $_SESSION['login-error'] = "Nieprawidłowy login lub hasło";
            }
            
          }else{
            $_SESSION['login-error'] = "Nieprawidłowy login lub hasło";
          }
          
        }else{
          $_SESSION['login-error'] = "Wystąpił błąd serwera";
        }

      }else{
        $_SESSION['login-error'] = "Wystąpił błąd serwera";
      }
      
      $_SESSION['login'] = $login;
      
    }else{
      $location .= "?cookies=false";
    }
    
  }
  header("location: $location");
  exit();
?>