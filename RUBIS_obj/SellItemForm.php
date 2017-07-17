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

    /* we added a little verification about the userID and regionID passed by. */

    use Rubis\LogPage_class;
    use Rubis\Cat_class;
    use Rubis\User_class;

    $log = new LogPage_class(basename(__FILE__, ".php"));

    try{
        switch($_SERVER['REQUEST_METHOD']) {
            case "POST":
                //do post
                $cat =  Cat_class::LoadCatByID($_POST['category']);
                $user = User_class::LoadUserByID($_POST['user']);
                break;
            case "GET":
                //do get;
                $cat =  Cat_class::LoadCatByID($_GET['category']);
                $user = User_class::LoadUserByID($_GET['user']);
                break;
            default:
                //error
                throw new Exception("please load this page from register form or via API with datas", 5);
        }
        $log->SellItemFormPage($user->getId(), $cat->getID());
    } catch (Exception $e) {
        $log->KillSession($e->getMessage());
    }
    ?>
  </body>
</html>
