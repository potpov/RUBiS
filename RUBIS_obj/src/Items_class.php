<?php
/**
 * Created by PhpStorm.
 * User: potpov
 * Date: 08/05/17
 * Time: 16:05
 */

namespace Rubis;

use \Exception;

class Items_class
{
    private $id;
    private $name;
    private $description;
    private $initPrice;
    private $reservPrice;
    private $qty;
    private $buyNow;
    private $nbBids;
    private $maxBid;
    private $startDate;
    private $endDate;
    private $seller;
    private $category;

    public function __construct(){

    }




    public static function AddNewItem($fields) {
        //security checks
        $required = array(  'userId' , 'categoryId', 'name',
                            'initialPrice', 'reservePrice', 'buyNow',
                            'duration', 'quantity');
        foreach ($required as $val)
            if (is_null($fields[$val]) || $fields[$val]==NULL || !isset($fields[$val]))
                throw new Exception("required fields missing $val", 18);
        if($fields['description'] == NULL or empty($fields['description']))
            $fields['description'] = 'No description';
        $fields['startDate'] = date("Y:m:d H:i:s");
        $fields['endDate'] = date("Y:m:d H:i:s", mktime(date("H"), date("i"),date("s"), date("m"), date("d")+$fields['duration'], date("Y")));
        //set item
        $item = new self();
        $item->setName($fields['name']);
        $item->setEndDate($fields['endDate']);
        $item->setStartDate($fields['startDate']);
        $item->setCategory($fields['categoryId']);
        $item->setInitPrice($fields['initialPrice']);
        $item->setReservPrice($fields['reservePrice']);
        $item->setQty($fields['quantity']);
        $item->setBuyNow($fields['buyNow']);
        $item->setSeller($fields['userId']);
        $item->setDescription($fields['description']);
        //store item
        $db = new DB_connect();
        $db->StoreItem($item);
        return $item;
    }

    public function AddThisToBuyNow($userID, $qty){
        if( !isset($userID) || !is_numeric($qty) || empty($qty)) {
            throw new Exception("<h2>failed to load data for buynow<br></h2>", 29);
        }
        $db = new DB_connect();
        $date = date("Y:m:d H:i:s");
        $db->StoreBuyNow($userID, $this->getId(), $qty, $date);
    }

    public static function LoadItemByID($id){
        $db = new DB_connect();
        if( !isset($id) || !is_numeric($id) || empty(trim($id))) {
            throw new Exception("<h2>Invalid item ID: $id<br></h2>", 14);
        }
        $item = new self();
        // if next method not found itemID on item then search for itemID on old items
        $db->LoadItemDatasByID($id, $item);
        return $item;
    }

    public function SearchItemByRegion($regionID, $catID, Page $pag){
        $db = new DB_connect();
        if( !isset($regionID) || $regionID==NULL || !isset($catID) || $catID==NULL) {
                throw new Exception("<h2>Invalid required parameters<br></h2>", 20);
        }
        $stmt = $db->SearchItemByRegion($catID, $regionID, $pag);
        if($stmt == NULL)
            return NULL;
        else if ($stmt == 0)
            return 0;
        else
            return $this->GeneralItemListing($stmt);
    }

    public function SearchItemByCategory($catID, Page $pag){
        $db = new DB_connect();
        if(!isset($catID) || $catID==NULL) {
            throw new Exception("<h2>Invalid required parameters<br></h2>", 24);
        }
        $stmt = $db->SearchItemByCat($catID, $pag);
        if($stmt == NULL)
            return NULL;
        else if ($stmt == 0)
            return 0;
        else
            return $this->GeneralItemListing($stmt);
    }


    /*
     * @return 0 if there are no item
     * @return NULL if errors
     * @return an array with all item-object if success
     */

    public function ItemsYouBid($userID) {
        if(!is_int($userID))
            throw new Exception("ERROR: user id for item list not valid", 11);
        $db = new DB_connect();
        $stmt = $db->GetListBidItems($userID);
        if($stmt == NULL)
            return NULL;
        else if ($stmt == 0)
            return 0;
        else
            return $this->GeneralItemListing($stmt, 'bidon');
    }


    public function ItemsYouWon($userID) {
        if(!is_int($userID))
            throw new Exception("ERROR: user id for item list not valid", 11);
        $db = new DB_connect();
        $stmt = $db->GetListWonItems($userID);
        if($stmt == NULL)
            return NULL;
        else if ($stmt == 0)
            return 0;
        else
            return $this->GeneralItemListing($stmt);
    }

    public function ItemsYouBought($userID) {
        if(!is_int($userID))
            throw new Exception("ERROR: user id for item list not valid", 11);
        $db = new DB_connect();
        $stmt = $db->GetListBoughtItems($userID);
        if($stmt == NULL)
            return NULL;
        else if ($stmt == 0)
            return 0;
        else
            return $this->GeneralItemListing($stmt, 'buynow');
    }

    public function ItemsYouSold($userID) {
        if(!is_int($userID))
            throw new Exception("ERROR: user id for item list not valid", 11);
        $db = new DB_connect();
        $stmt = $db->GetListSoldItems($userID);
        if($stmt == NULL)
            return NULL;
        else if ($stmt == 0)
            return 0;
        else
            return $this->GeneralItemListing($stmt);
    }

    public function ItemsYoureSelling($userID) {
        if(!is_int($userID))
            throw new Exception("ERROR: user id for item list not valid", 11);
        $db = new DB_connect();
        $stmt = $db->GetListSellingItems($userID);
        if($stmt == NULL)
            return NULL;
        else if ($stmt == 0)
            return 0;
        else
            return $this->GeneralItemListing($stmt);
    }

    public function UpdateItemQty($newqty){
        if(!is_int($newqty))
            throw new Exception("ERROR: no valid new qty", 27);
        if($newqty<0)
            throw new Exception("ERROR: you want to buy more than we can sell you.", 33);
        $db = new DB_connect();
        if($newqty == 0){
            //sold out now
            $db->SoldOut($this->getId());
        }
        else {
            //update
            $db->UpdateQty($this->getId(), $newqty);
        }
    }

    /** this method is used on support of an item object
     * it generetes a custom list with all data from the current item
     * plus information about NUMBER OF BIDS, FIRST BID OD USER, CURRENT BEST BID
     */

    public function BidsInfoOnThisItem() {
        $ID = $this->getId();
        $initP = $this->getInitPrice();
        if(!is_int($ID))
            throw new Exception("ERROR:item object not valid", 16);
        $db = new DB_connect();
        //getting array with nbBids, first and max bids
        $list = $db->GetBidsInfoOnItem($ID, $initP);
        $seller = User_class::LoadUserByID($this->seller);
        //finishing informations for printing
        return array_merge($list, array(
                'qty'           => $this->getQty(),
                'description'   => $this->getDescription(),
                'reservPrice'   => $this->getReservPrice(),
                'id'            => $this->getId(),
                'name'          => $this->getName(),
                'seller_id'     => $seller->getId(),
                'seller_user'   => $seller->getUserName(),
                'sdate'         => $this->getStartDate(),
                'edate'         => $this->getEndDate(),
                'buyNow'        => $this->getBuyNow()
        ));
    }

    public function GeneralItemListing(\mysqli_stmt $stmt, $PARAMETERS = NULL){
        $list = array();
        $stmt->execute();
        if($PARAMETERS == NULL) {
            //THIS IS FOR LISTING OF A ITEM.* QUERY
            $stmt->bind_result( $this->id, $this->name,
                $this->description, $this->initPrice,
                $this->qty, $this->reservPrice,
                $this->buyNow, $this->nbBids,
                $this->maxBid , $this->startDate,
                $this->endDate, $this->seller,
                $this->category
            );
        }
        else if($PARAMETERS == 'bidon') {
            //THIS IS ONLY FOR QUERIES THAT INCLUDE MAXBID
            $stmt->bind_result( $this->id, $this->name,
                $this->description, $this->initPrice,
                $this->qty, $this->reservPrice,
                $this->buyNow, $this->nbBids,
                $maxBid , $this->startDate,
                $this->endDate, $this->seller,
                $this->category, $this->maxBid
            );
        }
        else if($PARAMETERS == 'buynow'){
            //THIS IS ONLY FOR QUERY REFERRED TO BUY NOW TABLE ITEMS
            //CHECK THE RELATIVE METHODS FOR MORE INFORMATIONS ABOUT IT
            $stmt->bind_result( $this->id, $this->name,
                $this->buyNow, $this->qty, $this->seller
            );
        }
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
                if($PARAMETERS == NULL) {
                    //loading seller informations
                    $seller = User_class::LoadUserByID($this->seller);
                    //pushing all info for this item
                    array_push($list, array(
                        'item_id' => $this->id,
                        'item_name' => $this->name,
                        'item_description' => $this->description,
                        'item_initPrice' => $this->initPrice,
                        'item_qty' => $this->qty,
                        'item_reservPrice' => $this->reservPrice,
                        'item_buyNow' => $this->buyNow,
                        'item_nbBids' => $this->nbBids,
                        'item_maxBid' => $this->maxBid,
                        'item_startDate' => $this->startDate,
                        'item_endDate' => $this->endDate,
                        'item_seller' => $this->seller,
                        'item_category' => $this->category,
                        'seller_id' => $seller->getId(),
                        'seller_user' => $seller->getUserName()
                    ));
                }
                else if($PARAMETERS == 'bidon') {
                    //loading seller informations
                    $seller = User_class::LoadUserByID($this->seller);
                    //pushing all info for this item
                    array_push($list, array(
                        'item_id' => $this->id,
                        'item_name' => $this->name,
                        'item_description' => $this->description,
                        'item_initPrice' => $this->initPrice,
                        'item_qty' => $this->qty,
                        'item_reservPrice' => $this->reservPrice,
                        'item_buyNow' => $this->buyNow,
                        'item_nbBids' => $this->nbBids,
                        'item_maxBid' => $this->maxBid,
                        'item_startDate' => $this->startDate,
                        'item_endDate' => $this->endDate,
                        'item_seller' => $this->seller,
                        'item_category' => $this->category,
                        'user_maxBid' => $maxBid,
                        'seller_id' => $seller->getId(),
                        'seller_user' => $seller->getUserName()
                    ));
                }
                else if($PARAMETERS == 'buynow') {
                    //loading seller informations
                    $seller = User_class::LoadUserByID($this->seller);
                    //pushing all info for this item
                    array_push($list, array(
                        'item_id' => $this->id,
                        'item_name' => $this->name,
                        'item_buynow' => $this->buyNow,
                        'item_seller' => $this->seller,
                        'item_qty' => $this->qty,
                        'seller_id' => $seller->getId(),
                        'seller_user' => $seller->getUserName()
                    ));
                }
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
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getInitPrice()
    {
        return $this->initPrice;
    }

    /**
     * @return mixed
     */
    public function getReservPrice()
    {
        return $this->reservPrice;
    }

    /**
     * @return mixed
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * @return mixed
     */
    public function getBuyNow()
    {
        return $this->buyNow;
    }

    /**
     * @return mixed
     */
    public function getNbBids()
    {
        return $this->nbBids;
    }

    /**
     * @return mixed
     */
    public function getMaxBid()
    {
        return $this->maxBid;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @return mixed
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param mixed $initPrice
     */
    public function setInitPrice($initPrice)
    {
        $this->initPrice = $initPrice;
    }

    /**
     * @param mixed $reservPrice
     */
    public function setReservPrice($reservPrice)
    {
        $this->reservPrice = $reservPrice;
    }

    /**
     * @param mixed $qty
     */
    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    /**
     * @param mixed $buyNow
     */
    public function setBuyNow($buyNow)
    {
        $this->buyNow = $buyNow;
    }

    /**
     * @param mixed $nbBids
     */
    public function setNbBids($nbBids)
    {
        $this->nbBids = $nbBids;
    }

    /**
     * @param mixed $maxBid
     */
    public function setMaxBid($maxBid)
    {
        $this->maxBid = $maxBid;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * @param mixed $seller
     */
    public function setSeller($seller)
    {
        $this->seller = $seller;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }


}
