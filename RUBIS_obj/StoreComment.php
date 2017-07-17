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
  use Rubis\Comment_class;

  $log = new LogPage_class(basename(__FILE__, ".php"));

  try{
      switch($_SERVER['REQUEST_METHOD']) {
          case "POST":
              //do post
              $item =  Items_class::LoadItemByID($_POST['itemId']);
              $userFrom = User_class::LoadUserByID($_POST['from']);
              $userTo = User_class::LoadUserByID($_POST['to']);
              $rating = $_POST['rating'];
              $comment = $_POST['comment'];
              break;
          case "GET":
              //do get;
              $item = Items_class::LoadItemByID($_GET['itemId']);
              $userFrom = User_class::LoadUserByID($_GET['from']);
              $userTo = User_class::LoadUserByID($_GET['to']);
              $rating = $_GET['rating'];
              $comment = $_GET['comment'];
              break;
          default:
              //error
              throw new Exception("please load this page from register form or via API with datas", 5);
      }
      $commentObj = Comment_class::NewCommentIstance($userFrom->getId(), $userTo->getId(), $item, $comment, $rating);
      $commentObj->Store();
      $newRating = $userTo->getRating() + $rating;
      $userTo->UpdateRating($newRating);
      $log->StoreCommentPage();
  } catch (Exception $e) {
      $log->KillSession($e->getMessage());
  }
    ?>
  </body>
</html>
