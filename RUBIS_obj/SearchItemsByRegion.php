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
    use Rubis\Page;
    use Rubis\Items_class;

    $log = new LogPage_class(basename(__FILE__, ".php"));

    try{
        switch($_SERVER['REQUEST_METHOD']) {
            case "POST":
                //do post
                $catName = $_POST['categoryName'];
                $catID =$_POST['category'];
                $regionID = $_POST['region'];
                $page = new Page($_POST['page'], $_POST['nbOfItems']);
                break;
            case "GET":
                //do get;
                $catName = $_GET['categoryName'];
                $catID = $_GET['category'];
                $regionID = $_GET['region'];
                $page = new Page($_GET['page'], $_GET['nbOfItems']);
                break;
            default:
                //error
                throw new Exception("please load this page from register form or via API with datas", 5);
        }
        $item = new Items_class();
        $list = $item->SearchItemByRegion($regionID, $catID, $page);
        $log->SearchItemByRegionPage($list, $page, $catName, $catID, $regionID);
    } catch (Exception $e) {
        $log->KillSession($e->getMessage());
    }

    ?>
  </body>
</html>
