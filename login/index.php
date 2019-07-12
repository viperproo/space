<?php
  session_start();
  $functions = true;
  require_once "../files/php-lib/database.php";
  require_once "../files/php-lib/page-content.php";

  if(CheckUser()){
    header("location: ../");
    exit();
  }  

  function Content() {
    $rootf = RootFolder();
?>
  <article><?php
      SessionError('login-error');

      if(isset($_GET['cookies'])){
        InfoHeader("Obsługa plików cookie jest wyłączona. Logowanie nie jest możliwe", -1);
      }
    ?><form action="<?php echo $rootf ?>files/logincheck.php" method="post"><?php
      $login_input = array('type="text"', 'name="login"');
      if(isset($_SESSION['login'])){
        array_push($login_input, 'value="'.$_SESSION['login'].'"');
        unset($_SESSION['login']);
      }
      InputDiv("Login", $login_input);
      InputDiv("Hasło", array('type="password"', 'name="password"', 'maxlength=30'));
      ?><div class="input-div">
        <button type="submit" class="page-button"><span class="button-icon icon-login-1"></span><span>Zaloguj</span></button>
      </div>
    </form>
  </article>

<?php
  }
  require_once "../files/html-content/page.php";
?>
