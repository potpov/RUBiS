@include('header')
<?php
print("<TABLE width=\"100%\" bgcolor=\"#CCCCFF\">\n");
print("<TR><TD align=\"center\" width=\"100%\"><FONT size=\"4\" color=\"#000000\"><B>$item->name</B></FONT></TD></TR>\n");
print("</TABLE><p>\n");

print("<TABLE>\n".
    "<TR><TD>Currently<TD><b><BIG>".$current."</BIG></b>\n");
//reserve price printing
if ($item->reserve_price > 0) {
    $current >= $item->reserve_price ? $var='' : $var='NOT';
    print("(The reserve price has $var been met)\n");
}
print("<TR><TD>Quantity<TD><b><BIG>".$item->quantity."</BIG></b>\n");
print("<TR><TD>First bid<TD><b><BIG>".$first."</BIG></b>\n");
print("<TR><TD># of bids<TD><b><BIG>".$nbOfBids."</BIG></b> (<a href=\"/PHP/ViewBidHistory.php?itemId=".$item->id."\">bid history</a>)\n");

print("<TR><TD>Seller<TD><a href=\"/PHP/ViewUserInfo.php?userId=".$item->seller."\">".$sellerUser."</a> (<a href=\"/PHP/PutCommentAuth.php?to=".$item->seller."&itemId=".$item->id."\">Leave a comment on this user</a>)\n");
print("<TR><TD>Started<TD>".$item->start_date."\n");
print("<TR><TD>Ends<TD>".$item->end_date."\n");
print("</TABLE>\n");

if ($item->buy_now > 0) {
    print("<p><a href=\"/PHP/BuyNowAuth.php?itemId=".$item->id."\">".
        "<IMG SRC=\"".asset('storage/buy_it_now.jpg')."\" height=22 width=150></a>".
        "  <BIG><b>You can buy this item right now for only \$".$item->buy_now."</b></BIG><br><p>\n");
}
print("<a href=\"/PHP/PutBidAuth.php?itemId=".$item->id."\"><IMG SRC=\"".asset('storage/bid_now.jpg')."\" height=22 width=90> on this item</a>\n");

print("<TABLE width=\"100%\" bgcolor=\"#CCCCFF\">\n");
print("<TR><TD align=\"center\" width=\"100%\"><FONT size=\"4\" color=\"#000000\"><B>Item description</B></FONT></TD></TR>\n");
print("</TABLE><p>\n");
print($item->description);
print("<br><p>\n");

?>
@include('footer')
