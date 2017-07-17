@include('header')
<?php
if (count($bids) == 0)
    print ("<h2>There is no bid for ". $item->name . ". </h2><br>");
else
    print ("<h2><center>Bid history for ". $item->name . "</center></h2><br>");

print("<TABLE border=\"1\" summary=\"List of bids\">\n".
    "<THEAD>\n".
    "<TR><TH>User ID<TH>Bid amount<TH>Date of bid\n".
    "<TBODY>\n");
?>
@foreach($bids as $bid) <?php
    print("<TR><TD><a href=\"/PHP/ViewUserInfo.php?userId=".$bid->user_id."\">".$bid->sellerUser."</a>"
        ."<TD>".$bid->bid."<TD>".$bid->date."\n"); ?>
@endforeach
<?php print("</TABLE>\n"); ?>
@include('footer')
