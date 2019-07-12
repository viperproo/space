<?php
  function RootFolder() {
    return "/Space/";
  }

  function IsRootFolder() {
    return (! file_exists("../index.php"));
  }
?>