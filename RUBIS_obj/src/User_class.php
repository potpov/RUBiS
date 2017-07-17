<?php
namespace Rubis;

use \Exception;

class User_class {
	private $name;
	private $surname;
	private $username;
	private $password;
	private $id;
	private $region;
	private $regionID;
	private $email;
	private $rating;
	private $balace;
	private $creationdate;


    public function __construct(){

    }

	public static function AddUser($name, $surname, $username, $email, $password, $region) {
		if( !isset($name) || !isset($surname) || !isset($username) || !isset($email) || !isset($password) || !isset($region)
            || empty(trim($name)) || empty(trim($surname)) || empty(trim($username)) || empty(trim($email))
            || empty(trim($password)) || empty(trim($region))) {
            throw new Exception("you must fill all the field", 1);
        }

        //if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        //    throw new Exception("invalid email address", 2);
        $db = new DB_connect();
        //checking if region code exists and getting the code
        if($db->CheckRegion($region)!=1) {
            throw new Exception("no valid region selected, check the list", 3);
        }
        else {
            $regionID = $db->RegionId($region);
        }
        //checking if user not exists
        if($db->CheckUser($username) != 0) {
            throw new Exception("username not available", 4);
        }
        //ok, creating new user
        $user = new self();
        $user->setName($name);
        $user->setSurname($surname);
        $user->setUserName($username);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setRegion($region);
        $user->setRegionID($regionID);
        $user->setCreationdate(date("Y:m:d H:i:s"));
        $user->setBalace(0);
        $user->setRating(0);
        $id = $db->AddNewUser($user);
        $user->setId($id);
        return $user;

	}


    public static function LoadUserByCredential($username, $password){
        $db = new DB_connect();
        if( !isset($username) || !isset($password) || empty(trim($username)) || empty(trim($password))) {
            throw new Exception("<h2>You must provide your nick name!<br></h2>", 6);
        }
        $user = new self();
        $db->LoadUserDatasByCredentials($username, $password, $user);
        return $user;
    }

    public static function LoadUserByID($id){
        $db = new DB_connect();
        if( !isset($id) || !is_numeric($id) || empty(trim($id))) {
            throw new Exception("<h2>Invalid user ID: $id<br></h2>", 8);
        }
        $user = new self();
        $db->LoadUserDatasByID($id, $user);
        return $user;
    }

    public function UpdateRating($rating){
        if( !isset($rating) || !is_numeric($rating)) {
            throw new Exception("<h2>Invalid rating: $rating<br></h2>", 30);
        }
        $db = new DB_connect();
        $db->UpdateUserRating($this->getId(), $rating);
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

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->username;
    }

    /**
     * @param mixed $user
     */
    public function setUserName($user)
    {
        $this->username = $user;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
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
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return mixed
     */
    public function getBalace()
    {
        return $this->balace;
    }

    /**
     * @param mixed $balace
     */
    public function setBalace($balace)
    {
        $this->balace = $balace;
    }

    /**
     * @return mixed
     */
    public function getCreationdate()
    {
        return $this->creationdate;
    }

    /**
     * @param mixed $creationdate
     */
    public function setCreationdate($creationdate)
    {
        $this->creationdate = $creationdate;
    }

    /**
     * @return mixed
     */
    public function getRegionID()
    {
        return $this->regionID;
    }/**
     * @param mixed $regionID
     */
    public function setRegionID($regionID)
    {
        $this->regionID = $regionID;
    }


	
	
	
	
}
