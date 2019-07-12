<?php
  $functions = true;
  require_once "files/php-lib/database.php";

  function Content(){
?>
<article>
  <div class="content-div">
    <p class="title"><?php

      echo 'Witaj na stronie o kosmosie';

      if($user = GetUserData()){
        echo ' '.$user['name'];
      }

      echo ' :)';

    ?></p>
    <?php
      if(CheckUser()){
        echo '<p>Cieszymy się, że nas odwiedzasz :)</p>';
      }
    ?>
    <p>Znajdziesz tu informacje na temat różnych planet, gwiazd, mgławic, galaktyk i innych otaczających nasz świat rzeczy.</p>
  </div>
  <div class="content-div">
    <div class="image-container">
      <img src="files/photos/Solar_System.jpg" alt="Układ Słoneczny">
    </div>
  </div>
</article>
<?php
    }
    require_once "files/html-content/page.php";
?>
