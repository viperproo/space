<?php
  $functions = true;
  require_once "../files/php-lib/page-files.php";

  function Content() {
    echo
    '<article>
      <div class="content-div">
        <div class="gallery-div">';

    ScanImages("../files/photos", "Zdjęcie");
    echo
        '</div>
      </div>
    </article>';
  }
  require_once "../files/html-content/page.php";
?>
