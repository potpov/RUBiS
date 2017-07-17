@include('header')
<TABLE width="100%" bgcolor="#CCCCFF">
<TR><TD align="center" width="100%"><FONT size="4" color="#000000"><B><h2>Information about {{$userInfo->nickname}}<br></h2></B></FONT></TD></TR>
</TABLE><p>
Real life name : {{$userInfo->firstname}} {{$userInfo->lastname}}<br>
Email address  : {{$userInfo->email}}<br>
User since     : {{$userInfo->creation_date}}<br>
Current rating : <b>{{$userInfo->rating}}</b><br><p>

@php
    $bidN = count($itemYouBid);
    $wonN = count($itemYouWon);
    $boughtN = count($itemYouBought);
    $sellingN = count($itemYouSelling);
    $soldN = count($itemYouSold);
    $commentN = count($comment);
@endphp

    @if($bidN ==0)
        <?php
        print("<TABLE width=\"100%\" bgcolor=\"#CCCCFF\">\n");
        print("<TR><TD align=\"center\" width=\"100%\"><FONT size=\"4\" color=\"#000000\"><B><h2>You did not bid on any item.</h2>\n</B></FONT></TD></TR>\n");
        print("</TABLE><p>\n");
        ?>
    @else
        <?php
        print("<TABLE width=\"100%\" bgcolor=\"#CCCCFF\">\n");
        print("<TR><TD align=\"center\" width=\"100%\"><FONT size=\"4\" color=\"#000000\"><B><h3>Items you have bid on.</h3>\n</B></FONT></TD></TR>\n");
        print("</TABLE><p>\n");
        print("<TABLE border=\"1\" summary=\"Items You've bid on\">\n".
            "<THEAD>\n".
            "<TR><TH>Designation<TH>Initial Price<TH>Current price<TH>Your max bid<TH>Quantity".
            "<TH>Start Date<TH>End Date<TH>Seller<TH>Put a new bid\n".
            "<TBODY>\n");
        ?>
        @foreach($itemYouBid as $item)
            <?php
                print("<TR><TD><a href=\"/PHP/ViewItem.php?itemId=".$item->id."\">".$item->name.
                "<TD>".$item->initial_price."<TD>".$item->userMaxBid."<TD>".$item->max_bid."<TD>".$item->quantity.
                "<TD>".$item->start_date."<TD>".$item->end_date.
                "<TD><a href=\"/PHP/ViewUserInfo.php?userId=".$item->seller."\">".$item->sellerUser.
                "<TD><a href=\"/PHP/PutBid.php?itemId=".$item->id."&nickname=".urlencode($userInfo->nickname)."&password=".urlencode($userInfo->password)."\"><IMG SRC=\"".asset('storage/bid_now.jpg')."\" height=22 width=90></a>\n"); ?>
        @endforeach
        <?php print("</TBODY></TABLE><p>\n"); ?>
    @endif


    @if($bidN ==0)
        <?php
        print("<TABLE width=\"100%\" bgcolor=\"#CCCCFF\">\n");
        print("<TR><TD align=\"center\" width=\"100%\"><FONT size=\"4\" color=\"#000000\"><B><h3>You didn't win any item.</h3>\n</B></FONT></TD></TR>\n");
        print("</TABLE><p>\n");
        ?>
    @else
        <?php
        print("<TABLE width=\"100%\" bgcolor=\"#CCCCFF\">\n");
        print("<TR><TD align=\"center\" width=\"100%\"><FONT size=\"4\" color=\"#000000\"><B><h3>Items you won in the past 30 days.</h3>\n</B></FONT></TD></TR>\n");
        print("</TABLE><p>\n");
        print("<p><TABLE border=\"1\" summary=\"List of items\">\n".
            "<THEAD>\n".
            "<TR><TH>Designation<TH>Price you bought it<TH>Seller".
            "<TBODY>\n");
        ?>
        @foreach($itemYouWon as $item)
            <?php print("<TR><TD><a href=\"/PHP/ViewItem.php?itemId=".$item->id."\">".$item->name.
                    "<TD>".$item->max_bid.
                    "<TD><a href=\"/PHP/ViewUserInfo.php?userId=".$item->seller."\">".$item->sellerUser.
                    "\n"); ?>
        @endforeach
        <?php print("</TBODY></TABLE><p>\n"); ?>
    @endif


    @if($boughtN ==0)
        <?php
        print("<TABLE width=\"100%\" bgcolor=\"#CCCCFF\">\n");
        print("<TR><TD align=\"center\" width=\"100%\"><FONT size=\"4\" color=\"#000000\"><B><h3>You didn't buy any item in the past 30 days.</h3>\n</B></FONT></TD></TR>\n");
        print("</TABLE><p>\n");
        ?>
    @else
        <?php
        print("<TABLE width=\"100%\" bgcolor=\"#CCCCFF\">\n");
        print("<TR><TD align=\"center\" width=\"100%\"><FONT size=\"4\" color=\"#000000\"><B><h3>Items you bought in the past 30 days.</h3>\n</B></FONT></TD></TR>\n");
        print("</TABLE><p>\n");
        print("<p><TABLE border=\"1\" summary=\"List of items\">\n".
            "<THEAD>\n".
            "<TR><TH>Designation<TH>Quantity<TH>Price you bought it<TH>Seller".
            "<TBODY>\n");
        ?>
        @foreach($itemYouBought as $item)
            <?php print("<TR><TD><a href=\"/PHP/ViewItem.php?itemId=".$item->id."\">".$item->name.
                    "<TD>". $item->quantity."<TD>".$item->buy_now .
                    "<TD><a href=\"/PHP/ViewUserInfo.php?userId=".$item->seller."\">".$item->sellerUser.
                    "\n"); ?>
        @endforeach
        <?php print("</TBODY></TABLE><p>\n"); ?>
    @endif

    @if($sellingN ==0)
        <?php
        print("<TABLE width=\"100%\" bgcolor=\"#CCCCFF\">\n");
        print("<TR><TD align=\"center\" width=\"100%\"><FONT size=\"4\" color=\"#000000\"><B>You are currently selling no item.</h3>\n</B></FONT></TD></TR>\n");
        print("</TABLE><p>\n");
        ?>
    @else
        <?php
        print("<TABLE width=\"100%\" bgcolor=\"#CCCCFF\">\n");
        print("<TR><TD align=\"center\" width=\"100%\"><FONT size=\"4\" color=\"#000000\"><B><h3><h3>Items you are selling.</h3>\n</B></FONT></TD></TR>\n");
        print("</TABLE><p>\n");
        print("<p><TABLE border=\"1\" summary=\"List of items\">\n".
            "<THEAD>\n".
            "<TR><TH>Designation<TH>Initial Price<TH>Current price<TH>Quantity<TH>ReservePrice<TH>Buy Now".
            "<TH>Start Date<TH>End Date\n".
            "<TBODY>\n");
        ?>
        @foreach($itemYouSelling as $item)
            <?php
                $item->max_bid == NULL ? $cp = 'none' : $cp = $item->max_bid;
                print("<TR><TD><a href=\"/PHP/ViewItem.php?itemId=".$item->id."\">".$item->name.
                    "<TD>".$item->initial_price."<TD>".$cp."<TD>".$item->quantity.
                    "<TD>".$item->reserve_price."<TD>".$item->buy_now.
                    "<TD>".$item->start_date."<TD>".$item->end_date."\n"); ?>
        @endforeach
        <?php print("</TABLE><p>\n"); ?>
    @endif

    @if($soldN ==0)
        <?php
        print("<TABLE width=\"100%\" bgcolor=\"#CCCCFF\">\n");
        print("<TR><TD align=\"center\" width=\"100%\"><FONT size=\"4\" color=\"#000000\"><B><h3>You didn't sell any item in the last 30 days.</h3>\n</B></FONT></TD></TR>\n");
        print("</TABLE><p>\n");
        ?>
    @else
        <?php
        print("<TABLE width=\"100%\" bgcolor=\"#CCCCFF\">\n");
        print("<TR><TD align=\"center\" width=\"100%\"><FONT size=\"4\" color=\"#000000\"><B><h3>Items you sold in the last 30 days.</h3>\n</B></FONT></TD></TR>\n");
        print("</TABLE><p>\n");
        print("<p><TABLE border=\"1\" summary=\"List of items\">\n".
            "<THEAD>\n".
            "<TR><TH>Designation<TH>Initial Price<TH>Current price<TH>Quantity<TH>ReservePrice<TH>Buy Now".
            "<TH>Start Date<TH>End Date\n".
            "<TBODY>\n");
        ?>
        @foreach($itemYouSold as $item)
            <?php
            print("<TR><TD><a href=\"/PHP/ViewItem.php?itemId=".$item->id."\">".$item->name.
                "<TD>".$item->initial_price."<TD>none<TD>".$item->quantity.
                "<TD>".$item->reserve_price."<TD>". $item->buy_now.
                "<TD>".$item->start_date."<TD>".$item->end_date."\n");
            ?>
        @endforeach
        <?php print("</TABLE><p>\n"); ?>
    @endif

    @if($commentN ==0)
        <?php
        print("<TABLE width=\"100%\" bgcolor=\"#CCCCFF\">\n");
        print("<TR><TD align=\"center\" width=\"100%\"><FONT size=\"4\" color=\"#000000\"><B><h2>There is no comment for this user.</h2>\n</B></FONT></TD></TR>\n");
        print("</TABLE><p>\n");
        ?>
    @else
        <?php
        print("<TABLE width=\"100%\" bgcolor=\"#CCCCFF\">\n");
        print("<TR><TD align=\"center\" width=\"100%\"><FONT size=\"4\" color=\"#000000\"><B><h3>Comments about you.</h3>\n</B></FONT></TD></TR>\n");
        print("</TABLE><p>\n");
        print("<p><DL>\n");
        ?>
        @foreach($comment as $comm)
            <?php
            print("<DT><b><BIG><a href=\"/PHP/ViewUserInfo.php?userId=".$comm->from_user_id."\">".$comm->FromUser."</a></BIG></b>"." wrote the ".$comm->date."<DD><i>".$comm->comment."</i><p>\n");
            ?>
        @endforeach
        <?php print("</DL>\n"); ?>
        @include('footer')
    @endif
