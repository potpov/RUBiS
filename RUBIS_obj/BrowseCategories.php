<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <body>
    <?php
    /* this script is used on 3 ways
     * if user is authenticated he can choose a category
     * for selling his item,
     * else if user is guest (no region set)
     * he wants to browse by category, at least if user is
     * guest (regione id set) he wants to browse by region after selected
     * the category
     */

    //loading composer
    $file = __DIR__ . '/vendor/autoload.php';
    if (!file_exists($file)) {
        throw new RuntimeException('Install dependencies to run.');
    }
    $autoload = require $file;

    use Rubis\LogPage_class;
    use Rubis\Cat_class;
    use Rubis\User_class;
    use Rubis\Region_class;

    $log = new LogPage_class(basename(__FILE__, ".php"));

    /* first i get list of all categories
     * then i try to load catid from post/get.
     * i also try to load an user from post/get
     * according to the exception code (if any) i
     * understand if user is browsing/selling
     */

    //getting categories list
    $cat = new Cat_class();
    $list = $cat->ListRegions();

    try {
        //chosing method
        switch ($_SERVER['REQUEST_METHOD']) {
            case "POST":
                $username = $_POST['nickname'];
                $password = $_POST['password'];
                $region = $_POST['region'];
                break;
            case "GET":
                $username = $_GET['nickname'];
                $password = $_GET['password'];
                $region = $_GET['region'];
                break;
            default:
                $username = NULL;
                $password = NULL;
                $region = NULL;
                throw new Exception("please load this page from register form or via API with datas", 5);
        }
    } catch (Exception $e) {
        $log->KillSession($e->getMessage());
    }

    //chosing the right way and generating page
    try{
        $user = User_class::LoadUserByCredential($username, $password);
        //user loaded ->selling
        $log->BrowseCatPageSelling($list, $user);
    } catch (Exception $e) {
        if($e->getCode() == 6) {
            //no user, maybe region? or default
            if (Region_class::CheckRegion($region) == TRUE)
                $log->BrowseCatPageRegion($list, $region);
            else
                $log->BrowseCatPageDefault($list);
        }
        else {
            //general error
            $log->KillSession($e->getMessage());
        }
    }

    ?>
  </body>
</html>
