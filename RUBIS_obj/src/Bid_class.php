<?php
/**
 * Created by PhpStorm.
 * User: potpov
 * Date: 09/05/17
 * Time: 11:29
 */

namespace Rubis;

use \Exception;

class Bid_class
{
    private $id;
    private $userID;
    private $itemID;
    private $qty;
    private $bid;
    private $maxbid;
    private $date;

    public function __construct() {
    }

    public function AllBidsOnItem($itemID){
        if(empty($itemID) or $itemID==NULL )
            throw new Exception("<h3>ERROR: Bids list query failed for item $itemID", 23);
        $db = new DB_connect();
        $stmt = $db->GetAllBidsOnItem($itemID);
        if($stmt == NULL)
            return NULL;
        else if ($stmt == 0)
            return 0;
        else
            return $this->GeneralBidsListing($stmt);
    }

    public static function CreateBid(User_class $user, $newmax, $newbid, $newqty, $newItemID) {
        if(empty($newmax) || empty($newbid) || empty($newqty) || empty($newItemID))
            throw new Exception("<h3>please fill all the required field<br></h3>", 22);
        $bid = new self();
        $bid->setUserID($user->getId());
        $bid->setMaxbid($newmax);
        $bid->setBid($newbid);
        $bid->setQty($newqty);
        $bid->setItemID($newItemID);
        $bid->setDate(date("Y:m:d H:i:s"));
        return $bid;
    }

    /** @$qty is the same of item->qty
     */

    public function IsValid($qty, $minBid){
        if($this->qty > $qty)
            throw new Exception("<h3>You cannot request ".$this->qty." items because only ".$qty." are proposed !<br></h3>", 23);
        if($this->bid < $minBid)
            throw new Exception("<h3>Your bid of \$".$this->bid." is not acceptable because it is below the \$".$minBid." minimum bid !<br></h3>", 24);
        if($this->maxbid < $minBid)
            throw new Exception("<h3>Your bid of \$".$this->maxbid." is not acceptable because it is below the \$".$minBid." minimum bid !<br></h3>", 25);
        if($this->maxbid < $this->bid)
            throw new Exception("<h3>Your maximum bid of \$".$this->maxbid." is not acceptable because it is below your current bid of \$".$this->bid." !<br></h3>", 26);
        return TRUE;
    }

    public function StoreThisBidForItem(Items_class $referredItem){
        $db = new DB_connect();
        $db->begin_transaction();
        //update max bid for item in case this is better
        if ($this->maxbid > $referredItem->getMaxBid())
            $db->SetMaxBid($this->maxbid, $this->itemID);
        $db->AddNewBid($this);
        $db->SetNbOfBid($this->itemID);
        $db->commit();
    }

    public function GeneralBidsListing(\mysqli_stmt $stmt){
        $list = array();
        $stmt->execute();
        $stmt->bind_result( $this->id, $this->userID,
                            $this->itemID, $this->qty,
                            $this->bid, $this->maxbid,
                            $this->date
        );
        for(;;){
            $ret = $stmt->fetch();
            if($ret==NULL)	{
                //end
                return $list;
            }
            else if ($ret == false){
                //fail
                return NULL;
            }
            else {
                //success
                //loading seller informations
                $bidder = User_class::LoadUserByID($this->userID);
                //pushing all info for this item
                array_push($list, array(
                    'bid_id' => $this->id,
                    'bid_userID' => $this->userID,
                    'bid_itemID' => $this->itemID,
                    'bid_qty' => $this->qty,
                    'bid_bid' => $this->bid,
                    'bid_maxbid' => $this->maxbid,
                    'bid_date' => $this->date,
                    'bidder_id' => $bidder->getId(),
                    'bidder_user' => $bidder->getUserName()
                ));
            }
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * @param mixed $userID
     */
    public function setUserID($userID)
    {
        $this->userID = $userID;
    }

    /**
     * @return mixed
     */
    public function getItemID()
    {
        return $this->itemID;
    }

    /**
     * @param mixed $itemID
     */
    public function setItemID($itemID)
    {
        $this->itemID = $itemID;
    }

    /**
     * @return mixed
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * @param mixed $qty
     */
    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    /**
     * @return mixed
     */
    public function getBid()
    {
        return $this->bid;
    }

    /**
     * @param mixed $bid
     */
    public function setBid($bid)
    {
        $this->bid = $bid;
    }

    /**
     * @return mixed
     */
    public function getMaxbid()
    {
        return $this->maxbid;
    }

    /**
     * @param mixed $maxbid
     */
    public function setMaxbid($maxbid)
    {
        $this->maxbid = $maxbid;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }


}