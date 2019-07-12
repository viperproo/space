<?php
  require_once "root-folder.php";

  if(! (isset($functions) && $functions)){
    header("location: ".RootFolder());
    exit();
  }

  function ScanImages($folder_name, $alt, $except = false) {

    if(file_exists($folder_name)){

      $scan = scandir($folder_name);
      $folder_name .= '/';  

      if($scan){

        for($x = 2; $x < count($scan); $x++){
          $file = $folder_name.$scan[$x];

          if(is_dir($file)){
            ScanImages($file, $alt);
          }else{
            if($file != $except){
              if(@getimagesize($file)){
                MinImageDiv($file, $alt);
              }
            }
          }

        }

      }else{
        return false;
      }

    }else{
      return false;
    }    

  }

  function DeletePhotos($folder) {
    if(isset($_POST['del_photos'])){
      $photos = $_POST['del_photos'];
      if(gettype($photos) == "array"){
        if(file_exists($folder)){
          if(is_dir($folder)){
            foreach($photos as $photo){
              $file = "$folder/$photo";
              if(file_exists($file)){
                if(is_file($file)){
                  unlink($file);
                }
              }
            }
          }
        }
      }
    }
  }

  function UploadImages($name, $target_dir) {
    $images = false;
    if(isset($_FILES[$name])){
      $file = $_FILES[$name];
      if(gettype($file['name']) == "array"){
        $_SESSION['file-error'] = array();
        $images = array();

        if(! file_exists($target_dir) || ! is_dir($target_dir)){
          mkdir($target_dir);
        }
        
        for($i = 0, $uploaded = 0; $i < count($file['name']); $i++){
          $name = $file['name'][$i];
          $tmp_name = $file["tmp_name"][$i];
          $error = $file['error'][$i];
          array_push($_SESSION['file-error'], array($name));
          if($error == 0){

            if(@getimagesize($tmp_name)){

              $scan = scandir($target_dir);

              if($scan){

                $new_name = (count($scan) - 1).'.'.pathinfo(basename($name), PATHINFO_EXTENSION);
                $full_file_path = $target_dir.'/'.$new_name;
                $upload = move_uploaded_file($tmp_name, $full_file_path);

                if($upload){
                  $uploaded++;
                  array_push($_SESSION['file-error'][$i], NULL);
                  array_push($images, array($name, $new_name));
                }else{
                  array_push($_SESSION['file-error'][$i], "Nie udało się dodać zdjęcia");
                }

              }else{
                array_push($_SESSION['file-error'][$i], "Nie udało się dodać zdjęcia. Wystąpił błąd");
              }

            }else{
              array_push($_SESSION['file-error'][$i], "Plik nie jest zdjęciem");
            }

          }else{
            $info;
            switch($error){
              case 1:
              case 2:
                $info = "Rozmiar pliku przekracza dopuszczalną wartość";
                break;
              case 3:
                $info = "Plik nie dodał się w całości";
                break;
              case 4:
                $uploaded++;
                $info = NULL;
                break;
              case 6:
                $info = "Brakujący folder tymczasowy";
                break;
              case 7:
                $info = "Nie udało się zapisać pliku na dysk";
                break;
              case 8:
                $info = "Zatrzymano wysyłanie pliku";
                break;
              default:
                $info = "Wystąpił błąd";
                break;
            }
            array_push($_SESSION['file-error'][$i], $info);
          }
        }
        if($uploaded == $i){
          unset($_SESSION['file-error']);
        }
      }
    }
    return $images;
  }

  function SetObjectMainPhoto($path, $newPhoto) {
    if(file_exists($path)){
      if(is_dir($path)){
        $file = fopen("$path/photo.txt", "w");
        if($file){
          $full_path = $path."/".$newPhoto;
          $write = "";
          if(file_exists($full_path) && @getimagesize($full_path)){
            $write = $newPhoto;
          }else{
            $scan = scandir($path);
            if($scan){
              for($i = 2; $i < count($scan); $i++){
                if(@getimagesize($scan[$i])){
                  $write = $scan[$i];
                  break;
                }
              }
            }
          }
          fwrite($file, $write);
          fclose($file);
        }
      }
    }
  }

  function UploadedObjectMainPhoto($uploaded, $images) {
    $return = false;
    if(is_array($images)){
      for($i = 0; $i < count($images); $i++){
        $img = $images[$i];
        if(is_array($img)){
          if(count($img) == 2){
            if($uploaded == $img[0]){
              $return = $img[1];
            }
          }
        }
      }
    }
    return $return;
  }

  function GetObjectMainPhotoCheckFile($file) {
    if(@getimagesize($file)){
      return $file;
    }else{
      return "../files/Logo.png";
    }
  }

  function GetObjectMainPhoto($table, $id) {
    $rootf = RootFolder();
    $folder = "../files/photos/$table/$id";
    $image_path = "../files/Logo.png";
    if(file_exists($folder)){
      if(is_dir($folder)){
        $img = scandir($folder);

        if($img && count($img) > 2){
          $folder .= "/";
          $file_path = $folder."photo.txt";

          if(file_exists($file_path)){
            if(is_file($file_path)){
              $photo_txt_size = filesize($file_path);

              if($photo_txt_size){

                $file = fopen($file_path, "r");
                $image_name = fread($file, $photo_txt_size);
                fclose($file);

                if(file_exists($folder.$image_name)){
                  $image_path = $folder.$image_name;
                }else{
                  $image_path = GetObjectMainPhotoCheckFile($folder.$img[2]);
                }

              }else{
                $image_path = GetObjectMainPhotoCheckFile($folder.$img[2]);
              }
            }else{
              $image_path = GetObjectMainPhotoCheckFile($folder.$img[2]);
            }

          }else{
            $image_path = GetObjectMainPhotoCheckFile($folder.$img[2]);
          }
        }
      }
    }
    return $image_path;
  }

  function DeleteFolder($path) {
    if(file_exists($path) && is_dir($path)){
      $scan = scandir($path);
      if($scan){
        for($x = 2; $x < count($scan); $x++){
          $a = $path."/".$scan[$x];
          if(is_dir($scan[$x])){
            DeleteFolder($a);
          }else{
            unlink($a);
          }
        }
      }

      rmdir($path);
    }
  }

  function ModifyObjectPhotos($photo, $path) {
    $upload = UploadImages('photos', $path);
    $uploaded = UploadedObjectMainPhoto($photo, $upload);
    if($uploaded){
      $photo = $uploaded;
    }
    SetObjectMainPhoto($path, $photo);
  }
?>