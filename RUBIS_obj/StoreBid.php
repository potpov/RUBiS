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
                $minBid = $_POST['minBid'];
                $maxBid = $_POST['maxBid'];
                $bid = $_POST['bid'];
                $qty = $_POST['qty'];
                $user = User_class::LoadUserByID($_POST['userId']);
                $item =  Items_class::LoadItemByID($_POST['itemId']);
                break;
            case "GET":
                //do get;
                $minBid = $_GET['minBid'];
                $maxBid = $_GET['maxBid'];
                $bid = $_GET['bid'];
                $qty = $_GET['qty'];
                $user = User_class::LoadUserByID($_GET['userId']);
                $item = Items_class::LoadItemByID($_GET['itemId']);
                break;
            default:
                //error
                throw new Exception("please load this page from register form or via API with datas", 5);
        }
        $newBid = Bid_class::CreateBid($user, $maxBid, $bid, $qty, $item->getId());
        if($newBid->IsValid($item->getQty(), $minBid))
            $newBid->StoreThisBidForItem($item);
        //printing page
        $log->StoreBidPage();
    } catch (Exception $e) {
        $log->KillSession($e->getMessage());
    }
    ?>
  </body>
</html>
