<?php

//loading composer
$file = __DIR__ . '/vendor/autoload.php';
if (!file_exists($file)) {
    throw new RuntimeException('Install dependencies to run.');
}
$autoload = require $file;

use Rubis\DB_connect;
use Rubis\LogPage_class;
use Rubis\User_class;



?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <body>
    <?php
    $log = new LogPage_class(basename(__FILE__, ".php"));
    //collecting data from the right method (post or get)
    try{
        switch($_SERVER['REQUEST_METHOD']) {
            case "POST":
                //do post
                $user = User_class::AddUser(
                                        $_POST['firstname'],
                                        $_POST['lastname'],
                                        $_POST['nickname'],
                                        $_POST['email'],
                                        $_POST['password'],
                                        $_POST['region']
                );
                break;
            case "GET":
                //do get;
                $user = User_class::AddUser(
                                    $_GET['firstname'],
                                    $_GET['lastname'],
                                    $_GET['nickname'],
                                    $_GET['email'],
                                    $_GET['password'],
                                    $_GET['region']
                );
                break;
            default:
                //error
                throw new Exception("please load this page from register form or via API with datas", 5);
        }
        //registration completed
        $log->WelcomeUserPage($user);
    } catch (Exception $e) {
        $log->KillSession($e->getMessage());
    }

    ?>
  </body>
</html>
