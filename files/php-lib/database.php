<?php
  require_once "root-folder.php";

  if(! (isset($functions) && $functions)){
    header("location: ".RootFolder());
    exit();
  }

  function Connect() {
    $connect = mysqli_connect("localhost", "root", "vertrigo", "Space");
    if($connect){
      mysqli_set_charset($connect, 'utf8');
    }
    return $connect;
  }

  function Bans($connect, $user_id) {
    $bans = mysqli_query($connect, "SELECT * FROM `bans` WHERE `users_id` = $user_id ORDER BY `id` DESC LIMIT 1");
    if($bans){
      $result = mysqli_fetch_assoc($bans)['time'];
      if(mysqli_num_rows($bans) == 0 || ($result !== NULL && date("Y-m-d H:i:s") > $result)){
        return 1;
      }
      return 0;
    }
    return -1;
  }

  function CheckUser() {
    if(isset($_SESSION['user'])){
      $connect = Connect();

      if($connect){

        $query = mysqli_query($connect, "SELECT * FROM `users` WHERE `id` = ".$_SESSION['user']);

        if($query){
          if(mysqli_num_rows($query) == 1){
            if(Bans($connect, mysqli_fetch_assoc($query)['id']) == 1){
              return true;
            }
          }
        }

        mysqli_close($connect);

      }
      session_destroy();
      header("location: ".$_SERVER['REQUEST_URL']);
      exit();
    }
    return false;
  }

  function GetUserData() {
    if(CheckUser()){
      $connect = Connect();

      if($connect){

        $query = mysqli_query($connect, "SELECT * FROM `users` WHERE `id` = ".$_SESSION['user']);

        if($query){
          return mysqli_fetch_assoc($query);
        }

      }
    }
    return false;
  }

  function CheckTable($connect, $name) {
    $check_table = mysqli_query($connect, "SHOW TABLES");
    if($check_table){
      if(mysqli_num_rows($check_table) > 0){
        while($x = mysqli_fetch_row($check_table)){
          if($name == $x[0]){
            mysqli_free_result($check_table);
            return true;
          }
        }
      }
    }
    return false;
  }

  function DescTable($connect, $table) {
    $fields = mysqli_query($connect, "SHOW COLUMNS FROM `$table`");
    if($fields){
      return mysqli_fetch_all($fields);
    }
    return false;
  }

  function ForeignKey($connect, $table, $field_name, $field_name_value = false) {
    $query = mysqli_query($connect, "SHOW INDEXES IN `$table`");

    if($query){
      while($x = mysqli_fetch_assoc($query)){

        $column = $x['Column_name'];

        if($field_name == $column){

          $explode = explode("_", $column);

          if(count($explode) > 1){

            $foreign_table = $explode[0];
            $foreign_table_primary_key = $explode[1];
            $values = array('table' => $foreign_table, 'values' => array());
            $sql = "SELECT `id`, `name` FROM `$foreign_table`";

            if($field_name_value !== false && $field_name_value !== NULL){
              $sql .= " WHERE `$foreign_table_primary_key` = $field_name_value";
            }else{
              array_push($values['values'], array('id' => NULL, 'name' => 'Brak danych'));
            }

            $result = mysqli_query($connect, $sql);
            if($result){
              while($y = mysqli_fetch_row($result)){
                array_push($values['values'], array('id' => $y[0], 'name' => $y[1]));
              }
              return $values;
            }
          }

          break;

        }

      }
    }
    return false;
  }

  function TableColumnsNames($connect, $lang, $table) {
    $query = mysqli_query($connect, "SELECT * FROM `".$table."_columns_names` WHERE `lang` = '$lang'");

    if($query){
      return mysqli_fetch_assoc($query);
    }
    return false;
  }

  function TableColumnsNamesUnits($column_name) {
    $field = explode("_", $column_name);
    $array = array('field-name' => $field[0]);

    if(count($field) == 2){
      $array['field-unit'] = $field[1];
    }

    return $array;
  }
?>
