<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <body>
    <?php
    $scriptName = "BrowseRegions.php";
    include("PHPprinter.php");
    $startTime = getMicroTime();

    printHTMLheader("RUBiS available regions");
    
    getDatabaseLink($link);
    begin($link);
    $result = mysqli_query($link, "SELECT * FROM regions");
	if (!$result)
	{
		error_log("[".__FILE__."] Query 'SELECT * FROM regions' failed: " . mysqli_error($link));
		die("ERROR: Query failed: " . mysqli_error($link));
	}
    commit($link);
    if (mysqli_num_rows($result) == 0)
      print("<h2>Sorry, but there is no region available at this time. Database table is empty</h2><br>");
    else
      print("<h2>Currently available regions</h2><br>");

    while ($row = mysqli_fetch_array($result))
    {
      print("<a href=\"/PHP/BrowseCategories.php?region=".$row["id"]."\">".$row["name"]."</a><br>\n");
    }
    mysqli_free_result($result);
    mysqli_close($link);
    
    printHTMLfooter($scriptName, $startTime);
    ?>
  </body>
</html>
