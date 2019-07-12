<?php
  require_once "../files/php-lib/root-folder.php";

  if(! (isset($functions) && $functions)){
    header("location: ".RootFolder());
    exit();
  }

  require_once "../files/php-lib/database.php";
  require_once "../files/php-lib/page-content.php";
  require_once "../files/php-lib/page-files.php";
  
  function CategoryObjectContent($connect, $table, $id) {

    $query = mysqli_query($connect, "SELECT * FROM $table WHERE id = $id");
    $rootf = RootFolder();

    if($query){

      if(mysqli_num_rows($query) > 0){

        $fields = mysqli_query($connect, "SHOW COLUMNS FROM $table");

        if($fields){

          if(CheckUser()){
            echo
            '<div class="content-div">
              <div class="content-div">
                <a href="'.$rootf.'edit/?type='.$table.'&id='.$id.'" class="page-button inline full"><span class="button-icon icon-pencil-4"></span><span>Edytuj</span></a><a href="'.$rootf.'files/deleteobject.php?type='.$table.'&id='.$id.'" class="page-button inline danger"><span class="button-icon icon-trash"></span><span>Usuń</span></a>
              </div>';

            SessionError('delete-object-error');
            SessionSuccess('modify-error', "Zmiany zostały zapisane");

            echo
            '</div>';
          }

          $x = mysqli_fetch_assoc($query);
          $columns_names = TableColumnsNames($connect, 'pl', $table);

          if($columns_names){

            echo
            '<div class="content-div">
              <div class="planet-name">'.$x['name'].'</div>
              <div class="image-container">';
            $mainPhoto = GetObjectMainPhoto($table, $id);
            echo
                '<img src="'.$mainPhoto.'" alt="'.$x['name'].'">
              </div>';

            while($field_info = mysqli_fetch_assoc($fields)){

              $field = $field_info['Field'];
              $column = TableColumnsNamesUnits($columns_names[$field]);

              if($field_info['Key'] != "PRI"){
                echo
                '<div class="planet-feature';
                
                if($field != 'description'){
                  echo ' float';
                }
                
                echo '">
                  <div class="feature-name description">'.$column['field-name'].'</div>
                  <div class="feature-value">';

                  $foreign_name = ForeignKey($connect, $table, $field, $x[$field]);

                  if($x[$field] != NULL){

                    if($foreign_name){
                      echo '<a href="'.$rootf.$foreign_name['table'].'/?id='.$foreign_name['values'][0]['id'].'" class="page-link">'.$foreign_name['values'][0]['name'].'</a>';
                    }else{

                      echo $x[$field];

                      if(isset($column['field-unit'])){
                        echo ' <span class="description">'.$column['field-unit'].'</span>';
                      }

                    }

                  }else{
                    echo 'Brak danych';
                  }

                echo
                  '</div>
                  <div class="clear-both"></div>
                </div>';
              }

            }
            echo
            '</div>
            <div class="content-div">
              <div class="gallery-div">';
              ScanImages("../files/photos/$table/$id", $x['name'], $mainPhoto);
            echo
              '</div>
            </div>';
          }else{
            InfoHeader("Wystąpił błąd", -1);
          }

        }else{
          InfoHeader("Wystąpił błąd", -1);
        }
      }else{
        InfoHeader("Nie znaleziono strony");
      }

    }else{
      InfoHeader("Wystąpił błąd", -1);
    }
  }

  function CategoryObjectsContent($connect, $table) {
    $rootf = RootFolder();
    if(CheckUser()){
      echo
      '<div class="content-div">
        <a href="'.$rootf.'add/?type='.$table.'" class="page-button"><span class="button-icon icon-plus"></span><span>Dodaj nowy obiekt</span></a>
      </div>';

      SessionError('insert-error');
      SessionSuccess('delete-object-error', "Obiekt został usunięty");
    }
    echo '<div class="content-div">';

    $object = mysqli_query($connect, "SELECT `id`, `name` FROM $table");

    if($object){

      if(mysqli_num_rows($object) > 0){

        while($x = mysqli_fetch_assoc($object)){

          echo 
          '<a href="'.$rootf.$table.'/?id='.$x['id'].'" class="block-link">
            <div class="photo-div">
              <img src="'.GetObjectMainPhoto($table, $x['id']).'" alt="'.$x['name'].'">
            </div>
            <div class="object-name">'.$x['name'].'</div>
          </a>';                 
        }
      }else{
        InfoHeader("Nie znaleziono obiektów");
      }
    }else{
      InfoHeader("Wystąpił błąd", -1);
    }

    echo '</div>';
  }

  function SpaceObjects($type = false) {
    if(gettype($type) == "string"){
      $table = $type;
    }else{
      $table = basename(getcwd());
    }
    echo '<article>';

    $connect = Connect();

    if($connect){
      if(isset($_GET['id'])){
        $id = htmlentities($_GET['id'], ENT_QUOTES, "UTF-8");
        CategoryObjectContent($connect, $table, $id);
      }else{
        CategoryObjectsContent($connect, $table);
      }
    }else{
      InfoHeader("Wystąpił błąd", -1);
    }

    echo '</article>';
  }

  $css = "objects";

  function Content() {
    SpaceObjects();
  }
  require_once "../files/html-content/page.php";
?>