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
    use Rubis\User_class;
    use Rubis\Items_class;
    use Rubis\Comment_class;
    $log = new LogPage_class(basename(__FILE__, ".php"));


    try{
        switch($_SERVER['REQUEST_METHOD']) {
            case "POST":
                //do post
                $user = User_class::LoadUserByCredential($_POST['nickname'], $_POST['password']);
                break;
            case "GET":
                //do get;
                $user = User_class::LoadUserByCredential($_GET['nickname'], $_GET['password']);
                break;
            default:
                //error
                throw new Exception("please load this page from register form or via API with datas", 5);
        }
        //login completed
        $item = new Items_class();
        //generating list of item-object (BID) / 0 if no item found
        $bidlist = $item->ItemsYouBid($user->getId());
        if($bidlist != 0) {
            $i = 0;
            while ($bidlist[$i]['item_id'] != NULL) {
                //loading seller user settings for every object
                $seller = User_class::LoadUserByID($bidlist[$i]['item_seller']);
                //finalizing list with this last informations
                $bidlist[$i]['seller_id'] = $seller->getId();
                $bidlist[$i]['seller_user'] = $seller->getUserName();
                $i++;
            }
        }
        //generating list of item-object (WON/SOLD/SELLING ETC) / 0 if no items found
        $wonlist = $item->ItemsYouWon($user->getId());
        $boughtlist = $item->ItemsYouBought($user->getId());
        $sellinglist = $item->ItemsYoureSelling($user->getId());
        $soldlist = $item->ItemsYouSold($user->getId());
        //comments for this user on list
        $comment = new Comment_class();
        $commentlist = $comment->LoadCommentsOnUser($user->getId());
        //printing page
        $log->UserInfoPage($user, $bidlist, $wonlist, $boughtlist, $sellinglist, $soldlist, $commentlist);
    } catch (Exception $e) {
        $log->KillSession($e->getMessage());
    }
    ?>
  </body>
</html>
