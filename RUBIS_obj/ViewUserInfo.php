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
    use Rubis\Comment_class;

    $log = new LogPage_class(basename(__FILE__, ".php"));

    try{
        switch($_SERVER['REQUEST_METHOD']) {
            case "POST":
                //do post
                $user = User_class::LoadUserByID($_POST['userId']);
                break;
            case "GET":
                //do get;
                $user = User_class::LoadUserByID($_GET['userId']);
                break;
            default:
                //error
                throw new Exception("please load this page from register form or via API with datas", 5);
        }
        $comment = new Comment_class();
        $commentList = $comment->LoadCommentsOnUser($user->getId());
        $log->ViewUserInfoPage($user, $commentList);
    } catch (Exception $e) {
        $log->KillSession($e->getMessage());
    }
?>
  </body>
</html>
