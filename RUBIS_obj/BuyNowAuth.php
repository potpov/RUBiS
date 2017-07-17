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
              break;
          case "GET":
              $itemID = $_GET['itemId'];
              break;
          default:
              //error
              throw new Exception("please load this page from register form or via API with datas", 5);
      }
      if(!isset($itemID) or empty($itemID))
          throw new Exception("You must provide an item identifier!", 26);
      //creating page
      $log->BuyNowAuthPage($itemID);
  } catch (Exception $e) {
      $log->KillSession($e->getMessage());
  }

  ?>

  </body>
</html>
