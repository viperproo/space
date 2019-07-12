<?php
  require_once "../files/php-lib/root-folder.php";

  if(! (isset($functions) && $functions)){
    header("location: ".RootFolder());
    exit();
  }

  require_once "../files/php-lib/database.php";
  require_once "../files/php-lib/page-content.php";
  require_once "../files/php-lib/page-files.php";

  function ModifyObjectTable($id = false) {
    $rootf = RootFolder();
    $table = htmlentities($_GET['type'], ENT_QUOTES, "UTF-8");

    $_SESSION['modify-data'] = array("table" => $table);
    if($id !== false){
      $_SESSION['modify-data']['id'] = $id;
    }

    echo
    '<article>
      <div class="content-div">
        <div class="content-title">';

    if($id !== false){
      echo "Edytuj";
    }else{
      echo "Nowy";
    }

    echo ' obiekt: ';

    global $menu;

    foreach($menu as $page){
      if($table == $page['link']){
        echo $page['link-name'];
      }
    }

    echo '</div>';

    $connect = Connect();

    if($connect){

      $check_table = CheckTable($connect, $table);

      if($check_table){

        $fields = mysqli_query($connect, "SHOW COLUMNS FROM $table");

        if($fields){

          $columns_names = TableColumnsNames($connect, 'pl', $table);

          if($columns_names){

            if($id !== false){
              $query = mysqli_query($connect, "SELECT * FROM $table WHERE id = $id");
              if($query){
                if(mysqli_num_rows($query) > 0){
                  $x = mysqli_fetch_assoc($query);
                }else{
                  InfoHeader("Nie znaleziono strony", 0);
                  return false;
                }
              }else{
                InfoHeader("Wystąpił błąd", -1);
                return false;
              }
            }

            SessionError('modify-error');

            if(isset($_SESSION['file-error'])){
              InfoHeader('Niektóre zdjęcia nie zostały dodane', -1);
              foreach($_SESSION['file-error'] as $error){
                if($error[0] !== NULL && $error[1] !== NULL){
                  InfoHeader($error[0].' - <span class="error">'.$error[1].'</span>');
                }
              }
              unset($_SESSION['file-error']);
            }

            echo
            '<form action="'.$rootf.'files/modifyobject.php" method="post" enctype="multipart/form-data">';

            $i = 0;

            while($field_name = mysqli_fetch_assoc($fields)){

              $field = $field_name['Field'];
              $column = TableColumnsNamesUnits($columns_names[$field]);

              if($field_name['Key'] != "PRI"){

                echo
                '<div class="input-container-div';
                if($field != 'description'){
                  echo ' one-row';
                }
                  
                echo
                '">
                  <div class="input-title-div">'.$column['field-name'];

                  if(isset($column['field-unit'])){
                    echo ' ('.$column['field-unit'].')';
                  }

                  if($field_name['Null'] == "NO"){
                    echo '<span class="error">*</span>';
                  }

                echo
                '</div>
                  <div class="input-div">';

                $foreign_key = ForeignKey($connect, $table, $field);
                $current_value = false;

                if($foreign_key){

                  echo
                  '<div class="select-tag hidden">
                    <a class="select-selected-option"><span class="select-value">';

                    if($id){
                      $current_value = ForeignKey($connect, $table, $field, $x[$field])['values'][0]['name'];
                      echo $current_value;
                    }else{
                      echo 'Wybierz...';
                    }

                  echo
                    '</span><span class="select-icon icon-down-open"></span><div class="clear-both"></div></a>
                    <div class="select-options-container scrollable">';

                  for($k = 0; $k < count($foreign_key['values']); $k++){
                    $values = $foreign_key['values'][$k];
                    echo
                    '<label class="option-tag">
                      <input type="radio" name="col-'.$i.'" value="'.$values['id'].'"';

                    if($current_value == $values['name']){
                      echo ' checked';
                    }

                    echo 
                      '>
                      <span>'.$values['name'].'</span>
                    </label>';
                  }

                  echo
                    '</div>
                  </div>';

                }else{
                  if($field != 'description'){
                    echo '<input type="text" name="col-'.$i.'" placeholder="'.$column['field-name'].'"';

                    if($id){
                      echo ' value="'.$x[$field].'"';
                    }else if(isset($_SESSION['values'])){
                      echo ' value="'.$_SESSION['values'][$i].'"';
                    }

                    echo '>';
                  }else{
                    echo '<textarea name="col-'.$i.'" placeholder="'.$column['field-name'].'">';

                    if($id){
                      echo $x[$field];
                    }else if(isset($_SESSION['values'][$i])){
                      echo $_SESSION['values'][$i];
                    }

                    echo '</textarea>';
                  }
                }

                echo
                  '</div>';

                if(isset($_SESSION['validate-data'][$i])){
                  if($_SESSION['validate-data'][$i]){
                    InfoHeader("Sukces", 1);
                  }else{
                    InfoHeader("Nieprawidłowa wartość", -1);
                  }
                }

                echo
                '</div>';
                $i++;

              }
            }

            unset($_SESSION['values']);
            unset($_SESSION['validate-data']);

            echo
            '<div class="input-container-div">
              <div class="input-title-div">Zdjęcia</div>
              <div class="input-div">
                <div class="photo-input-div">
                  <div class="image-container min-image">
                    <label class="page-button" id="add-button">
                      <input type="file" name="photos[]" accept="image/*" class="input-file" multiple>
                      <span>+</span>
                    </label>
                  </div>';
            if($max_file_size = ini_get("upload_max_filesize")){
              echo '<div class="max-size-info">Maksymalny rozmiar pojedynczego zdjęcia: <strong>'.$max_file_size.'B</strong></div>';
            }
            if($max_post_size = ini_get("post_max_size")){
              echo '<div class="max-size-info">Maksymalny rozmiar wszystkich zdjęć: <strong>'.$max_post_size.'B</strong></div>';
            }
            echo
                '</div>';

              $path = "../files/photos/$table/$id";

              if(file_exists($path)){
                $photos = scandir($path);

                if($photos){

                  for($p = 2; $p < count($photos); $p++){
                    $file = $path."/".$photos[$p];

                    if(@getimagesize($file)){

                      echo
                      '<div class="photo-input-div">';
                      MinImageDiv($file, $x['name']);
                      echo
                        '<div class="option-div">
                          <label>
                            <input type="radio" name="main-photo" value="'.$photos[$p].'"';

                            if($file == GetObjectMainPhoto($table, $id)){
                              echo ' checked';
                            }

                      echo
                            '>
                            <div class="page-button inline"><span class="icon-checkbox"></span>Główne</div>
                          </label><label>
                            <input type="checkbox" name="del_photos[]" value="'.$photos[$p].'">
                            <div class="page-button inline danger"><span class="icon-checkbox"></span>Usuń</div></button>
                          </label>
                        </div>
                      </div>';

                    }

                  }
                }
              }

            echo
                '</div>
              </div>
              <div class="input-container-div">
                <a href="'.$rootf.$table.'/?id='.$id.'" class="page-button inline"><span class="button-icon icon-cancel"></span><span>Anuluj</span></a>
                <button type="submit" name="submit-btn" class="page-button inline full submit"><span class="button-icon icon-ok"></span><span>Zapisz</span></button>
              </div>
            </form>';
          }else{
            InfoHeader("Nie znaleziono strony");
          }

        }else{
          InfoHeader("Wystąpił błąd", -1);
        }

      }else{
        InfoHeader("Wystąpił błąd", -1);
      }

      mysqli_close($connect);

    }else{
      InfoHeader("Wystąpił błąd podczas próby połączenia z bazą", -1);
    }

    echo
        '<script src="'.$rootf.'files/js/select-tag.js"></script>
        <script src="'.$rootf.'files/js/files-u.js"></script>
      </div>
    </article>';
  }
?>