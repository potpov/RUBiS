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

    $log = new LogPage_class(basename(__FILE__, ".php"));

    try{
        switch($_SERVER['REQUEST_METHOD']) {
            case "POST":
                //do post
                $user = User_class::LoadUserByCredential($_POST['nickname'], $_POST['password']);
                $item =  Items_class::LoadItemByID($_POST['itemId']);
                break;
            case "GET":
                //do get;
                $user = User_class::LoadUserByCredential($_GET['nickname'], $_GET['password']);
                $item = Items_class::LoadItemByID($_GET['itemId']);
                break;
            default:
                //error
                throw new Exception("please load this page from register form or via API with datas", 5);
        }
        $list = $item->BidsInfoOnThisItem();
        $log->BidItemPage($list, $user);
    } catch (Exception $e) {
        $log->KillSession($e->getMessage());
    }
    ?>
  </body>
</html>
