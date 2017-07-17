<?php
/**
 * Created by PhpStorm.
 * User: potpov
 * Date: 11/05/17
 * Time: 17:16
 */

namespace Rubis;
use \Exception;

class Comment_class
{
    private $ID;
    private $from;
    private $to;
    private $itemID;
    private $rating;
    private $date;
    private $comment;

    public function __construct(){
    }

    public static function NewCommentIstance($from, $to, Items_class $item, $comment, $rating){
        if (!isset($comment) || !is_numeric($rating) || empty(trim($comment)))
            throw new Exception("<h2>ERROR: empty or invalid parameters for storing comments<br></h2>", 32);
        $newComment = new self();
        $newComment->date = date("Y:m:d H:i:s");
        $newComment->from = $from;
        $newComment->to = $to;
        $newComment->itemID = $item->getId();
        $newComment->comment = $comment;
        $newComment->rating = $rating;
        return $newComment;
    }

    public function Store(){
        $db = new DB_connect();
        $db->StoreComment($this->getFrom(), $this->getTo(), $this->getItemID(), $this->getRating(), $this->getDate(), $this->getComment());
    }

    public function LoadCommentsOnUser($userID) {
        $db = new DB_connect();
        if (!isset($userID) || !is_numeric($userID) || empty(trim($userID))) {
            throw new Exception("<h2>Invalid user ID for comments:" . $userID . "<br></h2>", 13);
        }
        $stmt = $db->GetCommentsAboutUser($userID);
        $list = array();
        if($stmt == NULL)
            return NULL;
        else if ($stmt == 0)
            return 0;
        $stmt->execute();
        $stmt->bind_result( $this->ID,
                            $this->from,
                            $this->to,
                            $this->item,
                            $this->rating,
                            $this->date,
                            $this->comment
        );
        for (; ;) {
            $ret = $stmt->fetch();
            if ($ret == NULL) {
                //end
                return $list;
            } else if ($ret == false) {
                //fail
                return NULL;
            } else {
                //loading seller informations
                $seller = User_class::LoadUserByID($this->from);
                //pushing all info for this item
                array_push($list, array(
                                'comment_ID' =>$this->ID,
                                'comment_from' =>$this->from,
                                'comment_to' =>$this->to,
                                'comment_item' =>$this->item,
                                'comment_rating' =>$this->rating,
                                'comment_date' =>$this->date,
                                'comment_comment' =>$this->comment,
                                'commentator_id' => $seller->getId(),
                                'commentator_user' => $seller->getUserName()
                ));
            }
        }
    }

    /**
     * @return mixed
     */
    public function getID()
    {
        return $this->ID;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return mixed
     */
    public function getItemID()
    {
        return $this->itemID;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }


}