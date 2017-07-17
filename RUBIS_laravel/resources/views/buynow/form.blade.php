@include('header')
<?php
print("<TABLE width=\"100%\" bgcolor=\"#CCCCFF\">\n");
print("<TR><TD align=\"center\" width=\"100%\"><FONT size=\"4\" color=\"#000000\"><B>You are ready to buy this item: $item->name</B></FONT></TD></TR>\n");
print("</TABLE><p>\n");
print("<TABLE>\n");
print("<TR><TD>Quantity<TD><b><BIG>".$item->quantity."</BIG></b>\n");
print("<TR><TD>Seller<TD><a href=\"/PHP/ViewUserInfo.php?userId=".$seller->id."\">".$seller->nickname."</a> (<a href=\"/PHP/PutCommentAuth.php?to=".$seller->id."&itemId=".$item->id."\">Leave a comment on this user</a>)\n");
print("<TR><TD>Started<TD>".$item->start_date."\n");
print("<TR><TD>Ends<TD>".$item->end_date."\n");
print("</TABLE>\n");

print("<TABLE width=\"100%\" bgcolor=\"#CCCCFF\">\n");
print("<TR><TD align=\"center\" width=\"100%\"><FONT size=\"4\" color=\"#000000\"><B>Item description</B></FONT></TD></TR>\n");
print("</TABLE><p>\n");
print($item->description);
print("<br><p>\n");

print("<TABLE width=\"100%\" bgcolor=\"#CCCCFF\">\n");
print("<TR><TD align=\"center\" width=\"100%\"><FONT size=\"4\" color=\"#000000\"><B>Buy Now</B></FONT></TD></TR>\n");
print("</TABLE><p>\n");
print("<form action=\"/PHP/StoreBuyNow.php\" method=POST>\n".
    "<input type=hidden name=userId value=".$buyer->id.">\n".
    "<input type=hidden name=itemId value=".$item->id.">\n".
    "<input type=hidden name=maxQty value=".$item->quantity.">\n");
if ($item->quantity > 1)
    print("<center><table><tr><td>Quantity:</td><td><input type=text size=5 name=qty></td></tr></table></center>\n");
else
    print("<input type=hidden name=qty value=1>\n");
print("</table><p><center><input type=submit value=\"Buy now!\"></center><p>\n");
?>
@include('footer')
