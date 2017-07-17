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

    $log = new LogPage_class(basename(__FILE__, ".php"));
    //creating array of required fields and array for its content
    $fields = array('userId' , 'categoryId', 'name', 'initialPrice', 'reservePrice',
                        'buyNow', 'duration', 'quantity', 'description');
    $content = array();

    try{
        switch($_SERVER['REQUEST_METHOD']) {
            case "POST":
                //do post
                foreach ($fields AS $field)
                    $content = array_merge($content, array($field => $_POST[$field]));
                break;
            case "GET":
                //do get;
                foreach ($fields AS $field)
                    $content = array_merge($content,  array($field => $_GET[$field]));
                break;
            default:
                //error
                throw new Exception("please load this page from register form or via API with datas", 5);
        }
        //create and store the new item
        Items_class::AddNewItem($content);
        $log->ItemInsertSuccessPage($content);
    } catch (Exception $e) {
        $log->KillSession($e->getMessage());
    }


    ?>
  </body>
</html>
