<?php

namespace Rubis;


class Region_class
{
    private $id;
    private $name;


    public function __construct() {

    }

    public static function CheckRegion($ID){
        $db = new DB_connect();
        if( !isset($ID) || empty(trim($ID)) || !is_numeric($ID) || is_null($ID)) {
            return FALSE;
        }
        if($db->CheckRegionByID($ID)==1)
            return TRUE;
        else
            return FALSE;
    }

    public function ListRegions() {
        $result = array();
        $db = new DB_connect();
        $db->begin_transaction();
        $stmt = $db->GetRegions();
        if($stmt == NULL)
            return NULL;
        $stmt->execute();
        $stmt->bind_result($this->id, $this->name);
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
                    'id' => $this->id,
                    'name' 	 => $this->name
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
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }



}