@include('header')
<?php
print("<TABLE width=\"100%\" bgcolor=\"#CCCCFF\">\n");
print("<TR><TD align=\"center\" width=\"100%\"><FONT size=\"4\" color=\"#000000\"><B>You are ready to bid on: " . $item->name . "</B></FONT></TD></TR>\n");
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
print("<TR><TD># of bids<TD><b><BIG>".$nbBids."</BIG></b> (<a href=\"/PHP/ViewBidHistory.php?itemId=".$item->id."\">bid history</a>)\n");
print("<TR><TD>Seller<TD><a href=\"/PHP/ViewUserInfo.php?userId=".$seller->id."\">".$seller->nickname."</a> (<a href=\"/PHP/PutCommentAuth.php?to=".$seller->id."&itemId=".$item->id."\">Leave a comment on this user</a>)\n");
print("<TR><TD>Started<TD>".$item->start_date."\n");
print("<TR><TD>Ends<TD>".$item->end_date."\n");
print("</TABLE>\n");

if ($item->buy_now > 0) {
    print("<p><a href=\"/PHP/BuyNowAuth.php?itemId=".$item->id."\">".
        "<IMG SRC=\"/PHP/buy_it_now.jpg\" height=22 width=150></a>".
        "  <BIG><b>You can buy this item right now for only \$".$item->buy_now."</b></BIG><br><p>\n");
}

print("<TABLE width=\"100%\" bgcolor=\"#CCCCFF\">\n");
print("<TR><TD align=\"center\" width=\"100%\"><FONT size=\"4\" color=\"#000000\"><B>Item description</B></FONT></TD></TR>\n");
print("</TABLE><p>\n");
print($item->description);
print("<br><p>\n");

print("<TABLE width=\"100%\" bgcolor=\"#CCCCFF\">\n");
print("<TR><TD align=\"center\" width=\"100%\"><FONT size=\"4\" color=\"#000000\"><B>Bidding</B></FONT></TD></TR>\n");
print("</TABLE><p>\n");
//setting the minimum amount for a bid
$minBid = $current +1;
print("<form action=\"/PHP/StoreBid.php\" method=POST>\n".
    "<input type=hidden name=minBid value=$minBid>\n".
    "<input type=hidden name=userId value=".$bidder->id.">\n".
    "<input type=hidden name=itemId value=".$item->id.">\n".
    "<input type=hidden name=maxQty value=".$item->quantity.">\n".
    "<center><table>\n".
    "<tr><td>Your bid (minimum bid is $minBid):</td>\n".
    "<td><input type=text size=10 name=bid></td></tr>\n".
    "<tr><td>Your maximum bid:</td>\n".
    "<td><input type=text size=10 name=maxBid></td></tr>\n");
if ($item->quantity > 1)
    print("<tr><td>Quantity:</td><td><input type=text size=5 name=qty></td></tr>\n");
else
    print("<input type=hidden name=qty value=1>\n");
print("</table><p><input type=submit value=\"Bid now!\"></center><p>\n");
?>
@include('footer')
