<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <body>
  <?php
  //loading composer
  $file = __DIR__ . '/vendor/autoload.php';
  if (!file_exists($file)) {
      throw new RuntimeException('Install dependencies to run.');
  }
  $autoload = require $file;

  use Rubis\LogPage_class;
  use Rubis\Items_class;

  $log = new LogPage_class(basename(__FILE__, ".php"));

  try{
      switch($_SERVER['REQUEST_METHOD']) {
          case "POST":
              $itemID =  $_POST['itemId'];
              $ToUser = $_POST['to'];
              break;
          case "GET":
              $itemID = $_GET['itemId'];
              $ToUser = $_GET['to'];
              break;
          default:
              //error
              throw new Exception("please load this page from register form or via API with datas", 5);
      }
      if(!isset($itemID) or empty($itemID) or !isset($ToUser) or empty($ToUser))
          throw new Exception("You must provide an item/user identifier!", 25);
      //creating page
      $log->PutCommentAuthPage($ToUser, $itemID);
  } catch (Exception $e) {
      $log->KillSession($e->getMessage());
  }

  ?>

  </body>
</html>
