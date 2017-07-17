@include('header')
<?php
print("<center><h2>Give feedback about your experience with ".$item->name."</h2><br>\n");
print("<form action=\"/PHP/StoreComment.php\" method=POST>\n".
    "<input type=hidden name=to value=".$ToUser->id.">\n".
    "<input type=hidden name=from value=".$FromUser->id.">\n".
    "<input type=hidden name=itemId value=".$item->id.">\n".
    "<center><table>\n".
    "<tr><td><b>From</b><td>".$FromUser->nickname."\n".
    "<tr><td><b>To</b><td>".$ToUser->nickname."\n".
    "<tr><td><b>About item</b><td>".$item->name."\n".
    "<tr><td><b>Rating</b>\n".
    "<td><SELECT name=rating>\n".
    "<OPTION value=\"5\">Excellent</OPTION>\n".
    "<OPTION value=\"3\">Average</OPTION>\n".
    "<OPTION selected value=\"0\">Neutral</OPTION>\n".
    "<OPTION value=\"-3\">Below average</OPTION>\n".
    "<OPTION value=\"-5\">Bad</OPTION>\n".
    "</SELECT></table><p><br>\n".
    "<TEXTAREA rows=\"20\" cols=\"80\" name=\"comment\">Write your comment here</TEXTAREA><br><p>\n".
    "<input type=submit value=\"Post this comment now!\"></center><p>\n");
?>
@include('footer')
