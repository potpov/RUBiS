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
    use Rubis\Bid_class;

    $log = new LogPage_class(basename(__FILE__, ".php"));

    try{
        switch($_SERVER['REQUEST_METHOD']) {
            case "POST":
                //do post
                $item =  Items_class::LoadItemByID($_POST['itemId']);
                break;
            case "GET":
                //do get;
                $item = Items_class::LoadItemByID($_GET['itemId']);
                break;
            default:
                //error
                throw new Exception("please load this page from register form or via API with datas", 5);
        }
        $bid = new Bid_class();
        $list = $bid->AllBidsOnItem($item->getId());
        $log->BidHistoryPage($list, $item);
    } catch (Exception $e) {
        $log->KillSession($e->getMessage());
    }

    ?>
  </body>
</html>
