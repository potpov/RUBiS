<?php
namespace Rubis;

use \mysqli;
use \Exception;
use \mysqli_result;
use \mysqli_stmt;

class DB_connect extends mysqli {
    private $instance = NULL;
    protected static $options = array();

    /*****************************************************************/
    /************** CREATE A CONNECTION ******************************/
    /*****************************************************************/

    public function __construct() {
        $this->CreateConn();
    }

    public static function setOptions( array $opt ) {
        self::$options = array_merge(self::$options, $opt);
    }

    /*before release the framework insert common user data for db
     *so in case of failure connection check you will create a new instance
     *with minus privileges but the app will not crash*/

    protected function CreateConn() {
        $o = self::$options;
        /* create new istance*/
        $this->instance = new mysqli(
            isset($o['host'])   ? $o['host']   : '',
            isset($o['user'])   ? $o['user']   : 'rubis',
            isset($o['pass'])   ? $o['pass']   : 'rubis',
            isset($o['dbname']) ? $o['dbname'] : 'rubis',
            isset($o['port'])   ? $o['port']   : 3306,
            isset($o['sock'])   ? $o['sock']   : false );
        // check if a connection established
        if( mysqli_connect_errno() ) {
            throw new exception(mysqli_connect_error(), mysqli_connect_errno());
        }
    }

    /*****************************************************************/
    /********************** GENERAL QUERIES **************************/
    /*****************************************************************/

    protected function checkInstance() {
        if( $this->instance == NULL) {
            $this->CreateConn();
        }
    }

    public function query($query) {
        $this->checkInstance();
        if( !$this->instance->real_query($query) ) {
            throw new exception( $this->error, $this->errno );
        }
        $result = new mysqli_result($this->instance);
        return $result;
    }

    public function prepare($query) {
        $this->checkInstance();
        $stmt = new mysqli_stmt($this->instance, $query);
        return $stmt;
    }

    /*count number of all videos on DB*/
    protected function countRecords($query){
        $this->checkInstance();
        $result= $this->query($query);
        return $result->num_rows;
    }

    /*****************************************************************/
    /********************** SPECIFIC QUERIES *************************/
    /*****************************************************************/


    /*****************************************************************/
    /**********************         USER    **************************/
    /*****************************************************************/

    public function CheckUser($nickname){
        $this->checkInstance();
        return $this->countRecords("SELECT * FROM users WHERE nickname=\"$nickname\"");
    }


    public function AddNewUser(User_class $user) {
        $this->checkInstance();
        $query = 'INSERT INTO users VALUES (NULL, "'.
                                            $user->getName() .'", "'.
                                            $user->getSurname() .'", "'.
                                            $user->getUserName() .'", "'.
                                            $user->getPassword() .'", "'.
                                            $user->getEmail() .'", '.
                                            $user->getRating() .', '.
                                            $user->getBalace() .', "'.
                                            $user->getCreationdate() .'", '.
                                            $user->getRegionID() .')';
        $stmt = $this->prepare($query);
        $stmt->execute();
        //$this->query($query);
        return $stmt->insert_id;
    }

    public function LoadUserDatasByCredentials($username, $password, User_class $user){
        $this->checkInstance();
        $this->begin_transaction();
        $query = 'SELECT * FROM users WHERE nickname="'. $username .'" AND password="'. $password .'"';
        if($this->countRecords($query)!=1) {
            $this->rollback();
            throw new Exception("<h3>ERROR: Sorry, but this user '$username' does not exist.</h3><br>", 7);
        }
        else {
            $result = $this->prepare($query);
            $result->execute();
            $result->bind_result($id, $name, $surname, $username, $password, $email, $rating, $balance, $cdate, $region);
            $result->fetch();
            $user->setId($id);
            $user->setName($name);
            $user->setSurname($surname);
            $user->setUserName($username);
            $user->setPassword($password);
            $user->setEmail($email);
            $user->setRating($rating);
            $user->setBalace($balance);
            $user->setCreationdate($cdate);
            $user->setRegionID($region);
            $this->commit();
            return $user;
        }
    }

    public function LoadUserDatasByID($id, User_class $user){
        $this->checkInstance();
        $this->begin_transaction();
        $query = 'SELECT * FROM users WHERE id="'. $id . '"';
        if($this->countRecords($query)!=1) {
            $this->rollback();
            throw new Exception("<h3>ERROR: Sorry, but the user whit ID '$id' does not exist.</h3><br>", 7);
        }
        else {
            $result = $this->prepare($query);
            $result->execute();
            $result->bind_result($id, $name, $surname, $username, $password, $email, $rating, $balance, $cdate, $region);
            $result->fetch();
            $user->setId($id);
            $user->setName($name);
            $user->setSurname($surname);
            $user->setUserName($username);
            $user->setPassword($password);
            $user->setEmail($email);
            $user->setRating($rating);
            $user->setBalace($balance);
            $user->setCreationdate($cdate);
            $user->setRegionID($region);
            $this->commit();
            return $user;
        }
    }

    public function GetCommentsAboutUser($userID){
        $this->checkInstance();
        $query = "SELECT * FROM comments WHERE comments.to_user_id=$userID";
        if($this->countRecords($query) == 0) {
            return 0;
        }
        return $this->prepare($query);
    }

    public function UpdateUserRating($userID, $rating){
        $this->checkInstance();
        $this->begin_transaction();
        $query = "UPDATE users SET rating=$rating WHERE id=$userID";
        $this->query($query);
        $this->commit();
    }

    public function StoreComment($from, $to, $itemID, $rating, $date, $comment){
        $this->checkInstance();
        $this->begin_transaction();
        $query = "INSERT INTO comments VALUES (NULL, $from, $to, $itemID, $rating, \"$date\", \"$comment\")";
        $stmt = $this->prepare($query);
        $valid = $stmt->execute();
        if($valid)
            $this->commit();
        else{
            $this->rollback();
            throw new exception("failed to store new comment", 31);
        }
    }
    /*****************************************************************/
    /********************       CATEGORIES    ************************/
    /*****************************************************************/

    public function CatID($cat){
        $this->checkInstance();
        $result = $this->prepare("SELECT id FROM categories WHERE name=\"$cat\"");
        $result->execute();
        $result->bind_result($id);
        $result->fetch();
        return $id;
    }

    public function CatName($ID){
        $this->checkInstance();
        $result = $this->prepare("SELECT name FROM categories WHERE id=\"$ID\"");
        $result->execute();
        $result->bind_result($name);
        $result->fetch();
        return $name;
    }

    public function GetCats(){
        $this->checkInstance();
        $query = "SELECT * FROM categories";
        if($this->countRecords($query) < 1)
            return 0;
        return $this->prepare($query);
    }

    public function SearchItemByCat($catID, Page $pag){
        $this->checkInstance();
        $query='SELECT items.* 
                  FROM items 
                    WHERE category='. $catID . ' AND end_date>=NOW() 
                        LIMIT ' . $pag->getCurrent()*$pag->getVpp().', '. $pag->getVpp();
        if($this->countRecords($query) == 0) {
            return 0;
        }
        return $this->prepare($query);
    }


    /*****************************************************************/
    /**********************       REGIONS   **************************/
    /*****************************************************************/

    public function RegionId($region){
        $this->checkInstance();
        $result = $this->prepare("SELECT id FROM regions WHERE name=\"$region\"");
        $result->execute();
        $result->bind_result($id);
        $result->fetch();
        return $id;
    }

    public function RegionName($ID){
        $this->checkInstance();
        $result = $this->prepare("SELECT name FROM regions WHERE id=\"$ID\"");
        $result->execute();
        $result->bind_result($name);
        $result->fetch();
        return $name;
    }

    public function GetRegions(){
        $this->checkInstance();
        $query = "SELECT * FROM regions";
        if($this->countRecords($query) < 1)
            return 0;
        return $this->prepare($query);
    }

    public function CheckRegion($region){
        $this->checkInstance();
        return $this->countRecords("SELECT * FROM regions WHERE name=\"$region\"");
    }

    public function CheckRegionByID($ID) {
        $this->checkInstance();
        return $this->countRecords("SELECT * FROM regions WHERE id=\"$ID\"");
    }

    /*****************************************************************/
    /**********************      ITEMS      **************************/
    /*****************************************************************/

    public function TotItem(){
        $this->checkInstance();
        $query = "SELECT * FROM items";
        return $this->countRecords($query);
    }

    public function SearchItemByRegion($catID, $regionID, Page $pag){
        $this->checkInstance();
        $query='SELECT items.* 
                  FROM items,users 
                    WHERE items.category='. $catID .' AND items.seller=users.id 
                      AND users.region='. $regionID .' AND end_date>=NOW() 
                        LIMIT ' . $pag->getCurrent()*$pag->getVpp().', '. $pag->getVpp();
        if($this->countRecords($query) == 0) {
            return 0;
        }
        return $this->prepare($query);
    }

    public function LoadItemDatasByID($id, Items_class $item){
        $this->checkInstance();
        $this->begin_transaction();
        $queryNew = 'SELECT * FROM items WHERE items.id="'. $id . '"';
        $queryOld = 'SELECT * FROM old_items WHERE old_items.id="'. $id . '"';
        $nbNew = $this->countRecords($queryNew);
        $nbOld = $this->countRecords($queryOld);
        if($nbNew==0 AND $nbOld == 0) {
            $this->rollback();
            throw new Exception("<h3>ERROR: Sorry, but this item '$id' does not exist.</h3><br>", 7);
        }
        else{
            $nbNew!=0 ? $query=$queryNew : $query=$queryOld;
        }
        $result = $this->prepare($query);
        $result->execute();
        $result->bind_result(   $id, $name, $description,
                                $initPrice, $qty, $reservPrice,
                                $buyNow, $nbBids, $maxBid, $startD,
                                $endD, $seller, $cat
        );
        $result->fetch();
        $item->setId($id);
        $item->setName($name);
        $item->setDescription($description);
        $item->setInitPrice($initPrice);
        $item->setQty($qty);
        $item->setReservPrice($reservPrice);
        $item->setBuyNow($buyNow);
        $item->setNbBids($nbBids);
        $item->setMaxBid($maxBid);
        $item->setStartDate($startD);
        $item->setEndDate($endD);
        $item->setSeller($seller);
        $item->setCategory($cat);
        $this->commit();
        return $item;
    }

    public function SoldOut($itemID){
        $this->checkInstance();
        $this->begin_transaction();
        $query = "UPDATE items SET end_date=NOW(),quantity=0 WHERE id=$itemID";
        $this->query($query);
        $this->commit();
    }

    public function UpdateQty($itemID, $newQty){
        $this->checkInstance();
        $this->begin_transaction();
        $query = "UPDATE items SET quantity=$newQty WHERE id=$itemID";
        $this->query($query);
        $this->commit();
    }


    public function GetListBidItems($userID){
        $this->checkInstance();
        $query="SELECT items.*, bids1.max_bid FROM bids as bids1, items  
                    WHERE bids1.user_id=$userID AND bids1.item_id=items.id AND items.end_date>=NOW()
                    	AND bids1.max_bid = (
                            SELECT MAX(bids2.max_bid) 
                            	FROM bids AS bids2
                            		WHERE bids2.item_id = bids1.item_id
                            		  AND bids2.user_id = bids1.user_id)";
        if($this->countRecords($query) == 0) {
            return 0;
        }
        return $this->prepare($query);
    }

    public function GetListWonItems($userID){
        $this->checkInstance();
        $query = "SELECT items.* FROM bids, items 
                    WHERE bids.user_id=$userID 
                        AND bids.item_id=items.id 
                        AND TO_DAYS(NOW()) - TO_DAYS(items.end_date) < 30 
                           GROUP BY item_id";
        if($this->countRecords($query) == 0) {
            return 0;
        }
        return $this->prepare($query);
    }

    public function GetListBoughtItems($userID){
        $this->checkInstance();
        $query = "SELECT items.id, items.name, items.buy_now, buy_now.qty, items.seller 
                    FROM items, buy_now 
                      WHERE items.id=buy_now.item_id AND buy_now.buyer_id=$userID 
                      AND TO_DAYS(NOW()) - TO_DAYS(buy_now.date)<=30";
        if($this->countRecords($query) == 0) {
            return 0;
        }
        return $this->prepare($query);
    }

    public function GetListSellingItems($userID){
        $this->checkInstance();
        $query = "SELECT * FROM items WHERE items.seller=$userID AND items.end_date>=NOW()";
        if($this->countRecords($query) == 0) {
            return 0;
        }
        return $this->prepare($query);
    }

    public function GetListSoldItems($userID){
        $this->checkInstance();
        //$query = "SELECT * FROM buy_now WHERE buy_now.buyer_id=$userID AND TO_DAYS(NOW()) - TO_DAYS(buy_now.date)<=30";
        $query ="SELECT * 
                  FROM items 
                    WHERE items.seller=$userID 
                    AND TO_DAYS(NOW()) - TO_DAYS(items.end_date) < 30";
        if($this->countRecords($query) == 0) {
            return 0;
        }
        return $this->prepare($query);
    }



    public function StoreItem(Items_class $item){
        $this->checkInstance();
        $this->begin_transaction();
        $query = 'INSERT INTO items VALUES (NULL, "'.$item->getName().'", "'.
                                            $item->getDescription().'", '.$item->getInitPrice().', '.
                                            $item->getQty().', '.$item->getReservPrice().', '.
                                            $item->getBuyNow().', 0, 0, "'.
                                            $item->getStartDate().'", "'.$item->getEndDate().'", '.
                                            $item->getSeller().', '.$item->getCategory().')';
        $stmt = $this->prepare($query);
        $valid = $stmt->execute();
        if($valid)
            $this->commit();
        else{
            $this->rollback();
            throw new exception("failed to store new item", 19);
        }
    }

    public function StoreBuyNow($userID, $itemID, $qty, $date){
        $this->checkInstance();
        $this->begin_transaction();
        $query="INSERT INTO buy_now VALUES (NULL, $userID, $itemID, $qty, '$date')";
        $stmt = $this->prepare($query);
        $valid = $stmt->execute();
        if($valid)
            $this->commit();
        else{
            $this->rollback();
            throw new exception("failed to store new buy now", 28);
        }
    }

    /*****************************************************************/
    /********************          BIDS       ************************/
    /*****************************************************************/



    public function SetMaxBid($maxBid, $itemId){
        $this->checkInstance();
        $query='UPDATE items SET max_bid='.$maxBid.' WHERE id='.$itemId;
        return $this->query($query);
    }

    public function SetNbOfBid($itemId){
        $this->checkInstance();
        $query='UPDATE items SET nb_of_bids=nb_of_bids+1 WHERE id='.$itemId;
        return $this->query($query);
    }

    /* @nbBids number of bids on this item
     * @current best bid on this item (initial price if none)
     * @first first bid on this item (none if no bids yet) */

    public function GetBidsInfoOnItem($ID, $initPrice){
        $this->checkInstance();
        $query = "SELECT bid FROM bids WHERE item_id = $ID";
        $nbBids = $this->countRecords($query);
        //no bids yet
        if($nbBids == 0) {
            $current = $initPrice;
            $first = "none";
        }
        else {
            $query = 'SELECT MAX(bid) as current, MIN(bid) as first
                          FROM bids WHERE item_id =' . $ID;
            $result = $this->prepare($query);
            $result->execute();
            $result->bind_result($current, $first);
            $result->fetch();
        };
        return array(
            'current' => $current,
            'first'   => $first,
            'nbBids'     => $nbBids
        );
    }

    public function GetAllBidsOnItem($itemID){
        $this->checkInstance();
        $query = "SELECT * FROM bids WHERE item_id=$itemID ORDER BY date DESC";
        if($this->countRecords($query) == 0) {
            return 0;
        }
        return $this->prepare($query);
    }

    public function AddNewBid(Bid_class $bid) {
        $this->checkInstance();
        $query = 'INSERT INTO bids VALUES (
                                NULL, '.
                                $bid->getUserID().', '.
                                $bid->getItemID().', '.
                                $bid->getQty().', '.
                                $bid->getBid().', '.
                                $bid->getMaxbid().', "'.
                                $bid->getDate().'")';
        $stmt = $this->prepare($query);
        $stmt->execute();
    }
}
