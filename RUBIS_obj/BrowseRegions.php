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
    use Rubis\Region_class;

    $log = new LogPage_class(basename(__FILE__, ".php"));
    $region = new Region_class();
    $list = $region->ListRegions();

    if($list == NULL) {
        $error = "<h2>Sorry, but there is no region available at this time. Database table is empty</h2><br>";
        $log->KillSession("$error");
    }
    else
        $log->RegionListPage($list);
    ?>

  </body>
</html>
