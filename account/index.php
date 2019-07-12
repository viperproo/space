<?php
  session_start();
  $functions = true;
  require_once "../files/php-lib/database.php";
  require_once "../files/php-lib/page-content.php";

  if(! CheckUser()){
    header("location: ../");
    exit();
  }
    
  function Content() {
    $rootf = RootFolder();
?>
  <article>
    <div class="content-div">
      <div class="content-title"><span class="title small-font">Zalogowany: </span><?php echo GetUserData()['name'] ?></div>
      <a class="page-button danger" href="<?php echo $rootf ?>logout/"><span class="button-icon icon-logout-3"></span><span>Wyloguj</span></a>
    </div>
    <div class="content-div">
      <div class="content-title">Zmień hasło</div><?php

          if(isset($_SESSION['password-change-error'])){
            $status;
            $info;
            
            if($_SESSION['password-change-error']){
              $status = -1;
              InfoHeader("Nie udało się zmienić hasła", $status);
              $info = $_SESSION['password-change-error'];
            }else{
              $status = 1;
              $info = "Pomyślnie zmieniono hasło";
            }
            unset($_SESSION['password-change-error']);
            InfoHeader($info, $status);
          }

        ?><form action="<?php echo $rootf ?>files/chpswd.php" method="post">
        <?php
          InputDiv("Aktualne hasło", array('type="password"', 'name="current-password"'));
          InputDiv("Nowe hasło", array('type="password"', 'name="new-password"'));
          InputDiv("Powtórz nowe hasło", array('type="password"', 'name="new-password2"'));
        ?>
        <div class="input-div">
          <button type="submit" class="page-button"><span class="button-icon icon-switch"></span><span>Zmień</span></button>
        </div>
      </form>
    </div>
  </article>
<?php
  }    
  require_once "../files/html-content/page.php";
?>