<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <body>
    <?php
    $scriptName = "StoreBuyNow.php";
    include("PHPprinter.php");
    $startTime = getMicroTime();
    
	$userId = NULL;
	if (isset($_POST['userId']))
	{
    	$userId = $_POST['userId'];
	}
	else if (isset($_GET['userId']))
	{
    	$userId = $_GET['userId'];
	}
	else
	{
		printError($scriptName, $startTime, "StoreBuyNow", "<h3>You must provide a user identifier!<br></h3>");
		exit();
	}
	$itemId = NULL;
	if (isset($_POST['itemId']))
	{
    	$itemId = $_POST['itemId'];
	}
	else if (isset($_GET['itemId']))
	{
    	$itemId = $_GET['itemId'];
	}
	else
	{
		printError($scriptName, $startTime, "StoreBuyNow", "<h3>You must provide an item identifier !<br></h3>");
		exit();
	}
	$maxQty = NULL;
	if (isset($_POST['maxQty']))
	{
    	$maxQty = $_POST['maxQty'];
	}
	else if (isset($_GET['maxQty']))
	{
    	$maxQty = $_GET['maxQty'];
	}
	else
	{
		printError($scriptName, $startTime, "StoreBuyNow", "<h3>You must provide a maximum quantity !<br></h3>");
		exit();
	}
	$qty = NULL;
	if (isset($_POST['qty']))
	{
    	$qty = $_POST['qty'];
	}
	else if (isset($_GET['qty']))
	{
    	$qty = $_GET['qty'];
	}
	else
	{
		printError($scriptName, $startTime, "StoreBuyNow", "<h3>You must provide a quantity !<br></h3>");
		exit();
	}

    /* Check for invalid values */

    if ($qty > $maxQty)
    {
      printError($scriptName, $startTime,"StoreBuyNow","<h3>You cannot request $qty items because only $maxQty are proposed !<br></h3>");
      return ;
    }      

    getDatabaseLink($link);
    begin($link);

//    $result = mysqli_query("LOCK TABLES buy_now WRITE, items WRITE", $link);
//	if (!$result)
//	{
//		error_log("[".__FILE__."] Failed to acquire locks on items and buy_now tables: " . mysqli_error($link));
//		die("ERROR: Failed to acquire locks on items and buy_now tables: " . mysqli_error($link));
//	}
    $result = mysqli_query($link,"SELECT * FROM items WHERE items.id=$itemId");
	if (!$result)
	{
		error_log("[".__FILE__."] Query 'SELECT * FROM items WHERE items.id=$itemId' failed: " . mysqli_error($link));
		die("ERROR: Query failed for item '$itemId': " . mysqli_error($link));
	}
    if (mysqli_num_rows($result) == 0)
    {
      printError($scriptName, $startTime, "BuyNow", "<h3>Sorry, but this item does not exist.</h3><br>");
      commit($link);
      exit();
    }
    $row = mysqli_fetch_array($result);
    $newQty = $row["quantity"]-$qty;
    if ($newQty == 0)
	{
      $result2 = mysqli_query($link,"UPDATE items SET end_date=NOW(),quantity=$newQty WHERE id=$itemId");
	  if (!$result2)
	  {
		error_log("[".__FILE__."] Failed to update item 'UPDATE items SET end_date=NOW(),quantity=$newQty WHERE id=$itemId': " . mysqli_error($link));
		die("ERROR: Failed to update item '$itemId': " . mysqli_error($link));
	  }
	}
    else
	{
      $result2 = mysqli_query($link,"UPDATE items SET quantity=$newQty WHERE id=$itemId");
	  if (!$result2)
	  {
		error_log("[".__FILE__."] Failed to update item 'UPDATE items SET quantity=$newQty WHERE id=$itemId': " . mysqli_error($link));
		die("ERROR: Failed to update item '$itemId': " . mysqli_error($link));
	  }
	}
    // Add BuyNow to database
    $now = date("Y:m:d H:i:s");
    $result2 = mysqli_query($link,"INSERT INTO buy_now VALUES (NULL, $userId, $itemId, $qty, '$now')");
	if (!$result2)
	{
		error_log("[".__FILE__."] Failed to insert new BuyNow in database 'INSERT INTO buy_now VALUES (NULL, $userId, $itemId, $qty, '$now')': " . mysqli_error($link));
		die("ERROR: Failed to insert new BuyNow for user '$userId' and item '$itemId' in database: " . mysqli_error($link));
	}
//    $result2 = mysqli_query("UNLOCK TABLES", $link);
//	if (!$result2)
//	{
//		error_log("[".__FILE__."] Failed to unlock items and buy_now tables: "  . mysqli_error($link));
//		die("ERROR: Failed to unlock items and buy_now tables: "  . mysqli_error($link));
//	}

    printHTMLheader("RUBiS: BuyNow result");
    if ($qty == 1)
      print("<center><h2>Your have successfully bought this item.</h2></center>\n");
    else
      print("<center><h2>Your have successfully bought these items.</h2></center>\n");
    
    commit($link);
    mysqli_free_result($result);
    mysqli_close($link);
    
    printHTMLfooter($scriptName, $startTime);
    ?>
  </body>
</html>
