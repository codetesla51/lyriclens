<?php
spl_autoload_register("myAutoLoader");
function myAutoLoader($classname)
{
  $url = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
  if (strpos($url, "controllers") !== false || strpos($url, "views") !==false ) {
    $path = "../classes/";
  } else {
    $path = "classes/";
  }
  $extension = ".class.php";
  $fullpath = $path . $classname . $extension;

  if (!file_exists($fullpath)) {
    return false;
  }

  require_once $fullpath;
}
?>
