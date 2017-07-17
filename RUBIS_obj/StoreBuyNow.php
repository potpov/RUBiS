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
  use Rubis\User_class;
  use Rubis\Bid_class;

  $log = new LogPage_class(basename(__FILE__, ".php"));

  try{
      switch($_SERVER['REQUEST_METHOD']) {
          case "POST":
              //do post
              $maxQty = $_POST['maxQty'];
              $qty = $_POST['qty'];
              $user = User_class::LoadUserByID($_POST['userId']);
              $item =  Items_class::LoadItemByID($_POST['itemId']);
              break;
          case "GET":
              //do get;
              $maxQty = $_GET['maxQty'];
              $qty = $_GET['qty'];
              $user = User_class::LoadUserByID($_GET['userId']);
              $item = Items_class::LoadItemByID($_GET['itemId']);
              break;
          default:
              //error
              throw new Exception("please load this page from register form or via API with datas", 5);
      }
      if($qty > $maxQty)
          throw new Exception("ERROR: you want more than we can sell you", 34);
      $newQty = $item->getQty()-$qty;
      $item->UpdateItemQty($newQty);
      $item->AddThisToBuyNow($user->getId(), $qty);
      $log->BuyNowStorePage($qty);
  } catch (Exception $e) {
      $log->KillSession($e->getMessage());
  }
  ?>
  </body>
</html>
