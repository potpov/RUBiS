<?php
/**
 * Created by PhpStorm.
 * User: potpov
 * Date: 13/05/17
 * Time: 11:50
 */

namespace Rubis;


class Cat_class
{
    private $ID;
    private $name;

    public function __construct(){
    }

    public static function LoadCatByID($ID){
        $db = new DB_connect();
        if( !isset($ID) || $ID == NULL || !is_numeric($ID)) {
            throw new \Exception("<h2>No valid category chosen (id)<br></h2>", 13);
        }
        $cat = new self();
        $cat->setID($ID);
        $cat->setName($db->CatName($ID));
        return $cat;
    }

    public static function LoadCatByName($name){
        $db = new DB_connect();
        if( !isset($name) || empty(trim($name))) {
            throw new \Exception("<h2>No valid category chosen (name)<br></h2>", 13);
        }
        $cat = new self();
        $cat->setName($name);
        $cat->setID($db->CatID($name));
        return $cat;
    }

    public function ListRegions() {
        $result = array();
        $db = new DB_connect();
        $db->begin_transaction();
        $stmt = $db->GetCats();
        if($stmt == NULL)
            return NULL;
        $stmt->execute();
        $stmt->bind_result($this->ID, $this->name);
        for(;;){
            $ret=$stmt->fetch();
            if($ret==NULL)	{
                //end
                $stmt->close();
                $db->commit();
                return $result;
            }
            else if ($ret == false){
                //fail
                $db->rollback();
                return NULL;
            }
            else {
                //success
                array_push($result,array(
                    'id' => $this->ID,
                    'name' 	 => $this->name
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
     * @param mixed $ID
     */
    public function setID($ID)
    {
        $this->ID = $ID;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


}