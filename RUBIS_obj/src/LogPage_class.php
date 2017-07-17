<?php
namespace Rubis;

class LogPage_class
{
    private $start;
    private $end;
    private $filename;

    function __construct($filename){
        list($usec, $sec) = explode(" ", microtime());
        $this->start = ((float)$usec + (float)$sec);
        $this->filename = $filename;
    }

    public function Header($title){
        $config = ConfigApp_class::GetConf();
        include($config['root_full_dir']."/header.html");
        //echo "<title>RUBiS ERROR: Register user</title>";
        echo "<title>$title</title>";

    }
    public function Footer(){
        list($usec, $sec) = explode(" ", microtime());
        $this->end = ((float)$usec + (float)$sec) - $this->start ;
        printf("<br><hr>RUBiS (C) Rice University/INRIA<br><i>Page generated by ".$this->filename.".php in ".round($this->end, 3)." seconds</i><br>\n");
        print("</body>\n");
        print("</html>\n");
    }

    public function KillSession($message){
        $this->Header("RUBiS ERROR: " . basename($this->filename, ".php"));
        print("<h2>We cannot process your request due to the following error :</h2><br>\n");
        echo $message;
        $this->Footer();
        die();
    }

    public function WelcomeUserPage(User_class $user) {
        $this->Header("RUBiS: Welcome to" . $user->getUserName());

        print("<h2>Your registration has been processed successfully</h2><br>\n");
        print("<h3>Welcome " . $user->getUserName() . "</h3>\n");
        print("RUBiS has stored the following information about you:<br>\n");
        print("First Name : ".$user->getName()."<br>\n");
        print("Last Name  : ".$user->getSurname()."<br>\n");
        print("Nick Name  : ".$user->getUserName()."<br>\n");
        print("Email      : ".$user->getEmail()."<br>\n");
        print("Password   : ".$user->getPassword()."<br>\n");
        print("Region     : " . $user->getRegion() . "<br>\n");
        print("<br>The following information has been automatically generated by RUBiS:<br>\n");
        print("User id       :".$user->getId()."<br>\n");
        print("Creation date :".$user->getCreationdate()."<br>\n");
        print("Rating        :".$user->getRating()."<br>\n");
        print("Balance       :".$user->getBalace()."<br>\n");
        $this->Footer();
    }

    public function RegionListPage($list){
        $config = ConfigApp_class::GetConf();
        $this->Header("RUBiS available regions");
        echo "<h2>Currently available regions</h2><br>";
        $i=0;
        while($list[$i]!=NULL) {
            print("<a href=\"". $config['root_dir'] ."/BrowseCategories.php?region=".$list[$i]['id']."\">".$list[$i]['name']."</a><br>\n");
            $i++;
        }
        $this->Footer();
    }

    public function InfoTableHeader($tag){
        print("<TABLE width=\"100%\" bgcolor=\"#CCCCFF\">\n");
        print("<TR><TD align=\"center\" width=\"100%\"><FONT size=\"4\" color=\"#000000\"><B>$tag</B></FONT></TD></TR>\n");
        print("</TABLE><p>\n");
    }


    public function UserInfoPage(User_class $user, $itemList, $wonItemList, $boughtItemList, $sellingItemList, $soldItemList, $commentList) {
        $this->Header("RUBiS: About me");
        //general user information printing
        $tag = "<h2>Information about " . $user->getUserName() . "<br></h2>";
        $this->InfoTableHeader($tag);
        //user activity informations
        //general info
        print("Real life name : ".$user->getName()." ".$user->getSurname()."<br>");
        print("Email address  : ".$user->getEmail()."<br>");
        print("User since     : ".$user->getCreationdate()."<br>");
        print("Current rating : <b>".$user->getRating()."</b><br><p>");
        //end of general info
        $this->UserItemBid($itemList, $user);
        $this->UserItemWon($wonItemList);
        $this->UserItemBought($boughtItemList);
        $this->UserItemSelling($sellingItemList);
        $this->UserItemSold($soldItemList);
        if ($commentList == 0) {
            $tag = "<h2>There is no comment for this user.</h2>\n";
            $this->InfoTableHeader($tag);
        }
        else {
            $tag = "<h3>Comments about you.</h3>\n";
            $this->InfoTableHeader($tag);
            $this->UserCommentPosted($commentList);
        }
        $this->Footer();
    }

    /************************************************/
    /*                 item bid list                */
    /************************************************/
    public function UserItemBid ($itemList, User_class $user) {
        $config = ConfigApp_class::GetConf();
        if ($itemList == 0) {
            $tag = "<h2>You did not bid on any item.</h2>\n";
            $this->InfoTableHeader($tag);
        }
        else {
            $tag = "<h3>Items you have bid on.</h3>\n";
            $this->InfoTableHeader($tag);

            print("<TABLE border=\"1\" summary=\"Items You've bid on\">\n".
                "<THEAD>\n".
                "<TR><TH>Designation<TH>Initial Price<TH>Current price<TH>Your max bid<TH>Quantity".
                "<TH>Start Date<TH>End Date<TH>Seller<TH>Put a new bid\n".
                "<TBODY>\n");
            $i = 0;
            while ($itemList[$i]['item_id'] != NULL) {
                print("<TR><TD><a href=\"" . $config['root_dir'] . "/ViewItem.php?itemId=".$itemList[$i]['item_id']."\">".$itemList[$i]['item_name'].
                    "<TD>".$itemList[$i]['item_initPrice']."<TD>".$itemList[$i]['user_maxBid']."<TD>".$itemList[$i]['item_maxBid']."<TD>".$itemList[$i]['item_qty'].
                    "<TD>".$itemList[$i]['item_startDate']."<TD>".$itemList[$i]['item_endDate'].
                    "<TD><a href=\"" . $config['root_dir'] . "/ViewUserInfo.php?userId=".$itemList[$i]['seller_id']."\">".$itemList[$i]['seller_user'].
                    "<TD><a href=\"" . $config['root_dir'] . "/PutBid.php?itemId=".$itemList[$i]['item_id']."&nickname=".urlencode($user->getUserName())."&password=".urlencode($user->getPassword())."\"><IMG SRC=\"" . $config['root_dir'] . "/bid_now.jpg\" height=22 width=90></a>\n");
                $i++;
            }
            print("</TBODY></TABLE><p>\n");
        }
    }
    /************************************************/
    /*                 item won list                */
    /************************************************/
    public function UserItemWon($wonItemList) {
        $config = ConfigApp_class::GetConf();
        if ($wonItemList == 0) {
            $tag = "<h3>You didn't win any item.</h3>\n";
            $this->InfoTableHeader($tag);
        }
        else {
            $tag = "<h3>Items you won in the past 30 days.</h3>\n";
            $this->InfoTableHeader($tag);
            print("<p><TABLE border=\"1\" summary=\"List of items\">\n".
                "<THEAD>\n".
                "<TR><TH>Designation<TH>Price you bought it<TH>Seller".
                "<TBODY>\n");
            $i = 0;
            while ($wonItemList[$i]['item_id'] != NULL) {
                print("<TR><TD><a href=\"" . $config['root_dir'] . "/ViewItem.php?itemId=".$wonItemList[$i]['item_id']."\">".$wonItemList[$i]['item_name'].
                    "<TD>".$wonItemList[$i]['item_maxBid'].
                    "<TD><a href=\"" . $config['root_dir'] . "/ViewUserInfo.php?userId=".$wonItemList[$i]['seller_id']."\">".$wonItemList[$i]['seller_user'].
                    "\n");
                $i++;
            }
            print("</TBODY></TABLE><p>\n");
        }
    }
    /************************************************/
    /*               item bought list               */
    /************************************************/
    public function UserItemBought($boughtItemList){
        $config = ConfigApp_class::GetConf();
        if ($boughtItemList == 0) {
            $tag = "<h3>You didn't buy any item in the past 30 days.</h3>\n";
            $this->InfoTableHeader($tag);
        }
        else {
            $tag = "<h3>Items you bought in the past 30 days.</h3>\n";
            $this->InfoTableHeader($tag);
            print("<p><TABLE border=\"1\" summary=\"List of items\">\n".
                "<THEAD>\n".
                "<TR><TH>Designation<TH>Quantity<TH>Price you bought it<TH>Seller".
                "<TBODY>\n");
            $i = 0;
            while ($boughtItemList[$i]['item_id'] != NULL) {
                print("<TR><TD><a href=\"" . $config['root_dir'] . "/ViewItem.php?itemId=".$boughtItemList[$i]['item_id']."\">".$boughtItemList[$i]['item_name'].
                    "<TD>". $boughtItemList[$i]["item_qty"]."<TD>".$boughtItemList[$i]['item_buynow'] .
                    "<TD><a href=\"" . $config['root_dir'] . "/ViewUserInfo.php?userId=".$boughtItemList[$i]['seller_id']."\">".$boughtItemList[$i]['seller_user'].
                    "\n");
                $i++;
            }
            print("</TBODY></TABLE><p>\n");
        }

    }
    /************************************************/
    /*               item selling list              */
    /************************************************/
    public function UserItemSelling($sellingItemList) {
        $config = ConfigApp_class::GetConf();
        if ($sellingItemList == 0) {
            $tag = "<h3>You are currently selling no item.</h3>\n";
            $this->InfoTableHeader($tag);
        }
        else {
            $tag = "<h3>Items you are selling.</h3>\n";
            $this->InfoTableHeader($tag);
            print("<p><TABLE border=\"1\" summary=\"List of items\">\n".
                "<THEAD>\n".
                "<TR><TH>Designation<TH>Initial Price<TH>Current price<TH>Quantity<TH>ReservePrice<TH>Buy Now".
                "<TH>Start Date<TH>End Date\n".
                "<TBODY>\n");
            $i = 0;
            while ($sellingItemList[$i]['item_id'] != NULL) {
                $sellingItemList[$i]["item_maxBid"] == null ? $cp = 'none' : $cp = $sellingItemList[$i]["item_maxBid"]; //current price value
                print("<TR><TD><a href=\"" . $config['root_dir'] . "/ViewItem.php?itemId=".$sellingItemList[$i]['item_id']."\">".$sellingItemList[$i]['item_name'].
                    "<TD>".$sellingItemList[$i]["item_initPrice"]."<TD>".$cp."<TD>".$sellingItemList[$i]["item_qty"].
                    "<TD>".$sellingItemList[$i]["item_reservPrice"]."<TD>".$sellingItemList[$i]["item_buyNow"].
                    "<TD>".$sellingItemList[$i]["item_startDate"]."<TD>".$sellingItemList[$i]["item_endDate"]."\n");
                $i++;
            }
            print("</TABLE><p>\n");
        }
    }
    /************************************************/
    /*                item sold list                */
    /************************************************/
    public function UserItemSold($soldItemList){
        $config = ConfigApp_class::GetConf();
        if ($soldItemList == 0) {
            $tag = "<h3>You didn't sell any item in the last 30 days.</h3>\n";
            $this->InfoTableHeader($tag);
        }
        else {
            $tag = "<h3>Items you sold in the last 30 days.</h3>\n";
            $this->InfoTableHeader($tag);
            print("<p><TABLE border=\"1\" summary=\"List of items\">\n".
                "<THEAD>\n".
                "<TR><TH>Designation<TH>Initial Price<TH>Current price<TH>Quantity<TH>ReservePrice<TH>Buy Now".
                "<TH>Start Date<TH>End Date\n".
                "<TBODY>\n");
            $i = 0;
            while ($soldItemList[$i]['item_id'] != NULL) {
                print("<TR><TD><a href=\"" . $config['root_dir'] . "/ViewItem.php?itemId=".$soldItemList[$i]['item_id']."\">".$soldItemList[$i]['item_name'].
                    "<TD>".$soldItemList[$i]["item_initPrice"]."<TD>".none."<TD>".$soldItemList[$i]["item_qty"].
                    "<TD>".$soldItemList[$i]["item_reservPrice"]."<TD>". $soldItemList[$i]["item_buyNow"].
                    "<TD>".$soldItemList[$i]["item_startDate"]."<TD>".$soldItemList[$i]["item_endDate"]."\n");
                $i++;
            }
            print("</TABLE><p>\n");
        }
    }

    public function UserCommentPosted($commentList){
        $config = ConfigApp_class::GetConf();
        $i = 0;
        print("<p><DL>\n");
        while ($commentList[$i]['comment_comment'] != NULL) {
            print("<DT><b><BIG><a href=\"".$config['root_dir']."/ViewUserInfo.php?userId=".$commentList[$i]["commentator_id"]."\">".$commentList[$i]['commentator_user']."</a></BIG></b>"." wrote the ".$commentList[$i]["comment_date"]."<DD><i>".$commentList[$i]["comment_comment"]."</i><p>\n");
            $i++;
        }
        print("</DL>\n");
    }

    /* @settings parameter:
     *          default -> browse by categories (nothings selected)
     *          sell    -> sell item
     *          region  -> browse by region
    */

    public function BrowseCatPageSelling($list, User_class $user){
        $config = ConfigApp_class::GetConf();
        $this->Header("RUBiS available categories");
        if ($list == NULL)
            echo "<h2>Sorry, but there is no category available at this time. Database table is empty</h2><br>";
        else {
            echo "<h2>Currently available categories</h2><br>";
            foreach ($list as $cat)
                print("<a href=\"". $config['root_dir']."/SellItemForm.php?category=".$cat['id']."&user=".$user->getId()."\">".$cat['name']."</a><br>\n");
        }
        $this->Footer();
    }

    public function BrowseCatPageDefault($list){
        $config = ConfigApp_class::GetConf();
        $this->Header("RUBiS available categories");
        if ($list == NULL)
            echo "<h2>Sorry, but there is no category available at this time. Database table is empty</h2><br>";
        else {
            echo "<h2>Currently available categories</h2><br>";
            foreach ($list as $cat)
                print("<a href=\"" . $config['root_dir']."/SearchItemsByCategory.php?category=".$cat['id']."&categoryName=".urlencode($cat['name'])."\">".$cat['name']."</a><br>\n");
        }
        $this->Footer();
    }


    public function BrowseCatPageRegion($list, $regionID){
        $config = ConfigApp_class::GetConf();
        $this->Header("RUBiS available categories");
        if ($list == NULL)
            echo "<h2>Sorry, but there is no category available at this time. Database table is empty</h2><br>";
        else {
            echo "<h2>Currently available categories</h2><br>";
            foreach ($list as $cat)
                print("<a href=\"" . $config['root_dir']."/SearchItemsByRegion.php?category=".$cat['id']."&categoryName=".urlencode($cat['name'])."&region=$regionID\">".$cat['name']."</a><br>\n");
        }
        $this->Footer();
    }

    public function BidItemPage($list, User_class $user){
        $config = ConfigApp_class::GetConf();
        $this->Header("RUBiS: Bidding");
        $tag = "You are ready to bid on: " . $list['name'];
        $this->InfoTableHeader($tag);
        print("<TABLE>\n".
            "<TR><TD>Currently<TD><b><BIG>".$list['current']."</BIG></b>\n");
        //reserve price printing
        if ($list['reservPrice'] > 0) {
            $list['current'] >= $list['reservPrice'] ? $var='' : $var='NOT';
            print("(The reserve price has $var been met)\n");
        }

        print("<TR><TD>Quantity<TD><b><BIG>".$list["qty"]."</BIG></b>\n");
        print("<TR><TD>First bid<TD><b><BIG>".$list['first']."</BIG></b>\n");
        print("<TR><TD># of bids<TD><b><BIG>".$list['nbBids']."</BIG></b> (<a href=\"" . $config['root_dir'] . "/ViewBidHistory.php?itemId=".$list["id"]."\">bid history</a>)\n");
        print("<TR><TD>Seller<TD><a href=\"".$config['root_dir']."/ViewUserInfo.php?userId=".$list["seller_id"]."\">".$list["seller_user"]."</a> (<a href=\"" . $config['root_dir'] . "/PutCommentAuth.php?to=".$list["seller_id"]."&itemId=".$list["id"]."\">Leave a comment on this user</a>)\n");
        print("<TR><TD>Started<TD>".$list["sdate"]."\n");
        print("<TR><TD>Ends<TD>".$list["edate"]."\n");
        print("</TABLE>\n");

        if ($list["buyNow"] > 0) {
            print("<p><a href=\"" . $config['root_dir'] . "/BuyNowAuth.php?itemId=".$list["id"]."\">".
                "<IMG SRC=\"" . $config['root_dir'] . "/buy_it_now.jpg\" height=22 width=150></a>".
                "  <BIG><b>You can buy this item right now for only \$".$list['buyNow']."</b></BIG><br><p>\n");
        }
        $tag = "Item description";
        $this->InfoTableHeader($tag);
        print($list["description"]);
        print("<br><p>\n");

        $tag = "Bidding";
        $this->InfoTableHeader($tag);
        //setting the minimum amount for a bid
        $minBid = $list['current'] +1;
        print("<form action=\"" . $config['root_dir'] . "/StoreBid.php\" method=POST>\n".
            "<input type=hidden name=minBid value=$minBid>\n".
            "<input type=hidden name=userId value=".$user->getId().">\n".
            "<input type=hidden name=itemId value=".$list["id"].">\n".
            "<input type=hidden name=maxQty value=".$list["qty"].">\n".
            "<center><table>\n".
            "<tr><td>Your bid (minimum bid is $minBid):</td>\n".
            "<td><input type=text size=10 name=bid></td></tr>\n".
            "<tr><td>Your maximum bid:</td>\n".
            "<td><input type=text size=10 name=maxBid></td></tr>\n");
        if ($list["qty"] > 1)
            print("<tr><td>Quantity:</td><td><input type=text size=5 name=qty></td></tr>\n");
        else
            print("<input type=hidden name=qty value=1>\n");
        print("</table><p><input type=submit value=\"Bid now!\"></center><p>\n");
        $this->Footer();
    }

    public function PutBidAuthPage($itemID){
        $config = ConfigApp_class::GetConf();
        $this->Header("RUBiS: User authentification for bidding");
        include($config['root_full_dir']."/put_bid_auth_header.html");
        print("<input type=hidden name=\"itemId\" value=\"$itemID\">");
        include($config['root_full_dir']."/auth_footer.html");
        $this->Footer();
    }

    public function ItemInsertSuccessPage($list){
        $this->Header("RUBiS: Selling " . $list['name']);
        print("<center><h2>Your Item has been successfully registered.</h2></center><br>\n");
        print("<b>RUBiS has stored the following information about your item:</b><br><p>\n");
        print("<TABLE>\n");
        empty($list['description']) ? $desc = 'no description' : $desc = $list['description'];
        print("<TR><TD>Name<TD>".$list['name']."\n");
        print("<TR><TD>Description<TD>$desc\n");
        print("<TR><TD>Initial price<TD>".$list['initialPrice']."\n");
        print("<TR><TD>ReservePrice<TD>".$list['reservePrice']."\n");
        print("<TR><TD>Buy Now<TD>".$list['buyNow']."\n");
        print("<TR><TD>Quantity<TD>".$list['quantity']."\n");
        print("<TR><TD>Duration<TD>".$list['duration']."\n");
        print("</TABLE>\n");
        print("<br><b>The following information has been automatically generated by RUBiS:</b><br>\n");
        print("<TABLE>\n");
        print("<TR><TD>User id<TD>".$list['userId']."\n");
        print("<TR><TD>Category id<TD>".$list['categoryId']."\n");
        print("</TABLE>\n");
        $this->Footer();
    }

    /* for this dump you need an array with the following field:
    ** item_name, item_id, item_maxBid, item_nbBids, item_endDate, item_initPrice, item_maxBid
     * you should get this selecting items attributes from DB (custom where clause)
     * and listing them with GeneralListing
     * on item class without any optional parameter.
     */
    public function ItemTableDump($list){
        $config = ConfigApp_class::GetConf();
        print("<TABLE border=\"1\" summary=\"List of items\">".
            "<THEAD>".
            "<TR><TH>Designation<TH>Price<TH>Bids<TH>End Date<TH>Bid Now".
            "<TBODY>");
        $i = 0;
        while ($list[$i]['item_id'] != NULL) {
            if ((is_null($list[$i]['item_maxBid'])) || ($list[$i]['item_maxBid'] == 0))
                $list[$i]['item_maxBid'] = $list[$i]['item_initPrice'];
            print("<TR><TD><a href=\"".$config['root_dir']."/ViewItem.php?itemId=".$list[$i]['item_id']."\">".$list[$i]['item_name'].
                "<TD>".$list[$i]['item_maxBid'].
                "<TD>".$list[$i]['item_nbBids'].
                "<TD>".$list[$i]['item_endDate'].
                "<TD><a href=\"".$config['root_dir']."/PutBidAuth.php?itemId=".$list[$i]['item_id']."\"><IMG SRC=\"".$config['root_dir']."/bid_now.jpg\" height=22 width=90></a>");
            $i++;
        }
        print("</TABLE>");
    }

    public function SearchItemByCatPage($list, Page $page, $catName, $catID){
        $config = ConfigApp_class::GetConf();
        $this->Header("RUBiS: Items in category $catName");
        print("<h2>Items in category $catName</h2><br><br>");
        if($list == 0 and $page->getCurrent() == 0){
            //no element and it's the first page..
            print("<h2>Sorry, but there are no items available in this category !</h2>");
        }
        else if($list == 0 and $page->getCurrent() != 0) {
            print("<h2>Sorry, but there are no more items available in this category !</h2>");
            print("<p><CENTER>\n<a href=\"".$config['root_dir']."/SearchItemsByCategory.php?category=$catID".
                "&categoryName=".urlencode($catName)."&page=".($page->getCurrent()-1)."&nbOfItems=".$page->getVpp()."\">Previous page</a>\n</CENTER>\n");
        }
        else {
            //showing elements...
            $this->ItemTableDump($list);
            //printing buttons
            if ($page->getCurrent() == 0)
                print("<p><CENTER>\n<a href=\"".$config['root_dir']."/SearchItemsByCategory.php?category=$catID".
                    "&categoryName=".urlencode($catName)."&page=".($page->getCurrent() + 1)."&nbOfItems=".$page->getVpp()."\">Next page</a>\n</CENTER>\n");
            else
                print("<p><CENTER>\n<a href=\"".$config['root_dir']."/SearchItemsByCategory.php?category=$catID".
                    "&categoryName=".urlencode($catName)."&page=".($page->getCurrent() - 1)."&nbOfItems=".$page->getVpp()."\">Previous page</a>\n&nbsp&nbsp&nbsp".
                    "<a href=\"".$config['root_dir']."/SearchItemsByCategory.php?category=$catID".
                    "&categoryName=".urlencode($catName)."&page=".($page->getCurrent() + 1)."&nbOfItems=".$page->getVpp()."\">Next page</a>\n\n</CENTER>\n");
        }
        $this->Footer();
    }

    public function SearchItemByRegionPage($list, Page $page, $catName, $catID, $regionID){
        $config = ConfigApp_class::GetConf();
        $this->Header("RUBiS: Search items by region");
        if(!isset($catName))
            $catName='[failed to get category name]';
        echo "<h2>Items in category $catName</h2><br><br>";
        if($list == 0 and $page->getCurrent() == 0){
            //no element and it's the first page..
            print("<h3>Sorry, but there is no item in this category for this region.</h3><br>\n");
        }
        else if($list == 0 and $page->getCurrent() != 0){
            //no elements but it's not the first page..
            print("<h2>Sorry, but there are no more items available in this category for this region!</h2>");
            print("<p><CENTER>\n<a href=\"".$config['root_dir']."/SearchItemsByRegion.php?category=$catID&region=$regionID".
                "&categoryName=".urlencode($catName)."&page=".($page->getCurrent()-1)."&nbOfItems=".$page->getVpp()."\">Previous page</a>\n</CENTER>\n");
        }
        else{
            //showing elements...
            $this->ItemTableDump($list);
            //prev page if exists
            echo '<p><CENTER>';
            if ($page->getCurrent() == 0)
                print("<p><CENTER>\n<a href=\"".$config['root_dir']."/SearchItemsByRegion.php?category=$catID&region=$regionID".
                    "&categoryName=".urlencode($catName)."&page=".($page->getCurrent() + 1)."&nbOfItems=".$page->getVpp()."\">Next page</a>\n</CENTER>\n");
            else
                print("<p><CENTER>\n<a href=\"".$config['root_dir']."/SearchItemsByRegion.php?category=$catID&region=$regionID".
                    "&categoryName=".urlencode($catName)."&page=".($page->getCurrent() - 1)."&nbOfItems=".$page->getVpp()."\">Previous page</a>\n&nbsp&nbsp&nbsp".
                    "<a href=\"".$config['root_dir']."/SearchItemsByRegion.php?category=$catID&region=$regionID".
                    "&categoryName=".urlencode($catName)."&page=".($page->getCurrent() + 1)."&nbOfItems=".$page->getVpp()."\">Next page</a>\n\n</CENTER>\n");
        }
        $this->Footer();
    }

    public function StoreBidPage(){
        $this->Header("RUBiS: Bidding result");
        print("<center><h2>Your bid has been successfully processed.</h2></center>\n");
        $this->Footer();
    }

    public function SellItemFormPage($userID, $catID){
        $config = ConfigApp_class::GetConf();
        $this->Header("RUBiS: Sell your item");
        include($config['root_full_dir']."/sellItemForm.html");
        print("<input type=hidden name=\"userId\" value=\"$userID\">");
        print("<input type=hidden name=\"categoryId\" value=\"$catID\">");
        $this->Footer();
    }

    public function BidHistoryPage($list, Items_class $item) {
        $config = ConfigApp_class::GetConf();
        if ($list == 0)
            print ("<h2>There is no bid for ". $item->getName() . ". </h2><br>");
        else
            print ("<h2><center>Bid history for ". $item->getName() . "</center></h2><br>");

        $this->Header("RUBiS: Bid history for ". $item->getName() . ".");
        print("<TABLE border=\"1\" summary=\"List of bids\">\n".
            "<THEAD>\n".
            "<TR><TH>User ID<TH>Bid amount<TH>Date of bid\n".
            "<TBODY>\n");
        $i=0;
        while ($list[$i] != NULL) {
            //$bidAmount = $bidsListRow["bid"];
            //$bidDate = $bidsListRow["date"];
            //$userId = $bidsListRow["user_id"];
            if ($list[$i]['bidder_id'] == 0)
            {
                print("Cannot lookup the user!<br>");
                $this->Footer();
                die();
            }
            print("<TR><TD><a href=\"". $config['root_dir'] ."/ViewUserInfo.php?userId=".$list[$i]['bidder_id']."\">".$list[$i]['bidder_user']."</a>"
                ."<TD>".$list[$i]['bid_bid']."<TD>".$list[$i]['bid_date']."\n");
            $i++;
        }
        print("</TABLE>\n");
        $this->Footer();
    }

    public function ViewUserInfoPage(User_class $user, $commentList){
        $this->Header("RUBiS: View user information");
        print("<h2>Information about ".$user->getUserName()."<br></h2>");
        print("Real life name : ".$user->getName()." ".$user->getSurname()."<br>");
        print("Email address  : ".$user->getEmail()."<br>");
        print("User since     : ".$user->getCreationdate()."<br>");
        print("Current rating : <b>".$user->getRating()."</b><br>");
        if($commentList==0)
            print("<h2>There is no comment for this user.</h2><br>\n");
        else
            $this->UserCommentPosted($commentList);
        $this->Footer();
    }

    public function ItemViewPage($list){
        $config = ConfigApp_class::GetConf();
        $this->Header("RUBiS: Viewing" . $list['name']);
        $tag = $list['name'];
        $this->InfoTableHeader($tag);
        print("<TABLE>\n".
            "<TR><TD>Currently<TD><b><BIG>".$list['current']."</BIG></b>\n");
        //reserve price printing
        if ($list['reservPrice'] > 0) {
            $list['current'] >= $list['reservPrice'] ? $var='' : $var='NOT';
            print("(The reserve price has $var been met)\n");
        }
        print("<TR><TD>Quantity<TD><b><BIG>".$list["qty"]."</BIG></b>\n");
        print("<TR><TD>First bid<TD><b><BIG>".$list['first']."</BIG></b>\n");
        print("<TR><TD># of bids<TD><b><BIG>".$list['nbBids']."</BIG></b> (<a href=\"" . $config['root_dir'] . "/ViewBidHistory.php?itemId=".$list["id"]."\">bid history</a>)\n");

        print("<TR><TD>Seller<TD><a href=\"".$config['root_dir']."/ViewUserInfo.php?userId=".$list["seller_id"]."\">".$list['seller_user']."</a> (<a href=\"".$config['root_dir']."/PutCommentAuth.php?to=".$list["seller_id"]."&itemId=".$list["id"]."\">Leave a comment on this user</a>)\n");
        print("<TR><TD>Started<TD>".$list["sdate"]."\n");
        print("<TR><TD>Ends<TD>".$list["edate"]."\n");
        print("</TABLE>\n");

        if ($list["buyNow"] > 0) {
            print("<p><a href=\"".$config['root_dir']."/BuyNowAuth.php?itemId=".$list["id"]."\">".
                "<IMG SRC=\"".$config['root_dir']."/buy_it_now.jpg\" height=22 width=150></a>".
                "  <BIG><b>You can buy this item right now for only \$".$list['buyNow']."</b></BIG><br><p>\n");
        }
        print("<a href=\"".$config['root_dir']."/PutBidAuth.php?itemId=".$list["id"]."\"><IMG SRC=\"".$config['root_dir']."/bid_now.jpg\" height=22 width=90> on this item</a>\n");

        $tag = "Item description";
        $this->InfoTableHeader($tag);
        print($list["description"]);
        print("<br><p>\n");
        $this->Footer();
    }

    public function PutCommentAuthPage($ToUser, $itemID){
        $config = ConfigApp_class::GetConf();
        $this->Header("RUBiS: User authentification for comment");
        include($config['root_full_dir']."/put_comment_auth_header.html");
        print("<input type=hidden name=\"to\" value=\"$ToUser\">");
        print("<input type=hidden name=\"itemId\" value=\"$itemID\">");
        include($config['root_full_dir']."/auth_footer.html");
        $this->Footer();
    }

    public function BuyNowAuthPage($itemID){
        $config = ConfigApp_class::GetConf();
        $this->Header("RUBiS: User authentification for buying an item");
        include($config['root_full_dir']."/buy_now_auth_header.html");
        print("<input type=hidden name=\"itemId\" value=\"$itemID\">");
        include($config['root_full_dir']."/auth_footer.html");
        $this->Footer();
    }

    public function PutCommentPage(User_class $UserTo, User_class $UserFrom, Items_class $item){
        $config = ConfigApp_class::GetConf();
        $this->Header("RUBiS: Comment service");
        print("<center><h2>Give feedback about your experience with ".$item->getName()."</h2><br>\n");
        print("<form action=\"".$config['root_dir']."/StoreComment.php\" method=POST>\n".
            "<input type=hidden name=to value=".$UserTo->getId().">\n".
            "<input type=hidden name=from value=".$UserFrom->getId().">\n".
            "<input type=hidden name=itemId value=".$item->getId().">\n".
            "<center><table>\n".
            "<tr><td><b>From</b><td>".$UserFrom->getUserName()."\n".
            "<tr><td><b>To</b><td>".$UserTo->getUserName()."\n".
            "<tr><td><b>About item</b><td>".$item->getName()."\n".
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
        $this->Footer();
    }

    public function BuyNowPage(User_class $buyer, User_class $seller, Items_class $item){
        $config = ConfigApp_class::GetConf();
        $this->Header("RUBiS: Buy Now");
        $tag = "You are ready to buy this item: " . $item->getName();
        $this->InfoTableHeader($tag);
        print("<TABLE>\n");
        print("<TR><TD>Quantity<TD><b><BIG>".$item->getQty()."</BIG></b>\n");
        print("<TR><TD>Seller<TD><a href=\"".$config['root_dir']."/ViewUserInfo.php?userId=".$seller->getId()."\">".$seller->getUserName()."</a> (<a href=\"".$config['root_dir']."/PutCommentAuth.php?to=".$seller->getId()."&itemId=".$item->getId()."\">Leave a comment on this user</a>)\n");
        print("<TR><TD>Started<TD>".$item->getStartDate()."\n");
        print("<TR><TD>Ends<TD>".$item->getEndDate()."\n");
        print("</TABLE>\n");

        $tag = "Item description";
        $this->InfoTableHeader($tag);
        print($item->getDescription());
        print("<br><p>\n");

        $tag = "Buy Now";
        $this->InfoTableHeader($tag);
        print("<form action=\"".$config['root_dir']."/StoreBuyNow.php\" method=POST>\n".
            "<input type=hidden name=userId value=".$buyer->getId().">\n".
            "<input type=hidden name=itemId value=".$item->getId().">\n".
            "<input type=hidden name=maxQty value=".$item->getQty().">\n");
        if ($item->getQty() > 1)
            print("<center><table><tr><td>Quantity:</td><td><input type=text size=5 name=qty></td></tr></table></center>\n");
        else
            print("<input type=hidden name=qty value=1>\n");
        print("</table><p><center><input type=submit value=\"Buy now!\"></center><p>\n");
        $this->Footer();
    }

    public function BuyNowStorePage($qty){
        $this->Header("RUBiS: BuyNow result");
        if ($qty == 1)
            print("<center><h2>Your have successfully bought this item.</h2></center>\n");
        else
            print("<center><h2>Your have successfully bought these items.</h2></center>\n");
        $this->Footer();
    }

    public function StoreCommentPage(){
        $this->Header("RUBiS: Comment posting");
        print("<center><h2>Your comment has been successfully posted.</h2></center>\n");
        $this->Footer();
    }

}