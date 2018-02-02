<?php

/* 
 * Class user
 */

class user {
    
    /* Class Member Properties*/
    public $id;
    public $username;
    public $password;
    public $firstname;
    public $lastname;
    public $address;
    public $zipcode;
    public $city;
    public $country;
    public $email;
    public $phone1;
    public $phone2;
    public $phone3;
    public $birthdate;
    public $gender;
    public $created;
    public $suspended;
    public $deleted;

    public $arrLabels;
    public $arrFormElms;
    public $arrValues;
    public $arrGroups;


    /**
     * Class Constructor
     * @global object $db
     */
    public function __construct() {
        global $db;
        $this->db = $db;
        
        /**
         * Array for friendly labels: 
         * Index = fieldname
         * Value = friendly name
         */
        $this->arrLabels = array(
            "id" => "Bruger ID",
            "vcImage" => "Profilbillede",
            "username" => "Brugernavn",
            "password" => "Adgangskode",
            "firstname" => "Fornavn",
            "lastname" => "Efternavn",
            "address" => "Adresse",
            "zipcode" => "Postnummer",
            "city" => "By",
            "country" => "Land",
            "email" => "Email",
            "phone1" => "Telefon 1",
            "phone2" => "Telefon 2",
            "phone3" => "Telefon 3",
            "birthday" => "Fødselsdato",
            "gender" => "Køn",
            "created" => "Oprettelsesdato",
            "suspended" => "Suspenderet"
        );
        
        /**
         * Array for formfields: 
         * Index = fieldname
         * Value[0] = formtype
         * Value[1] = filter_type
         * Value[2] = Required Status (TRUE/FALSE)
         * Value[3] = Default value
         */
        $this->arrFormElms = array(
            "id" => array("hidden", FILTER_VALIDATE_INT, FALSE, 0),
            "vcImage" => array("hidden", FILTER_SANITIZE_STRING, FALSE, ""),
            "username" => array("text", FILTER_SANITIZE_STRING, TRUE, ""),
            "password" => array("password", FILTER_SANITIZE_STRING, FALSE, ""),
            "firstname" => array("text", FILTER_SANITIZE_STRING, TRUE, ""),
            "lastname" => array("text", FILTER_SANITIZE_STRING, TRUE, ""),
            "address" => array("text", FILTER_SANITIZE_STRING, TRUE, ""),
            "zipcode" => array("text", FILTER_SANITIZE_STRING, TRUE, 0),
            "city" => array("text", FILTER_SANITIZE_STRING, TRUE, ""),
            "country" => array("text", FILTER_SANITIZE_STRING, TRUE, ""),
            "email" => array("text", FILTER_SANITIZE_STRING, TRUE, ""),
            "phone1" => array("text", FILTER_SANITIZE_STRING, FALSE, ""),
            "phone2" => array("text", FILTER_SANITIZE_STRING, FALSE, ""),
            "phone3" => array("text", FILTER_SANITIZE_STRING, FALSE, ""),
            "created" => array("hidden", FILTER_SANITIZE_STRING, FALSE, 0),
            "suspended" => array("checkbox", FILTER_VALIDATE_INT, FALSE, 0),
        );
        
        $this->arrValues = array();
    }
    
    /**
     * Class Method GetList
     * @return array Returns selected rows as an array
     */
    public function getlist() {
        $sql = "SELECT * FROM user " . 
                "WHERE deleted = 0";
        return $this->db->_fetch_array($sql);
    }
    
    /**
     * Class Method GetUser
     * @param int $id
     * Selects by id and add values to class properties
     */
    public function getuser($id) {
        $this->id = $id;
        $sql = "SELECT * " .
                "FROM user " .
                "WHERE id = ? " .
                "AND deleted = 0";
        if($row = $this->db->_fetch_array($sql, array($this->id))) {
            foreach($row[0] as $key => $value) {
                $this->$key = $value;
            }
        }
    }
    
    /**
     * Class Method Save
     * @return int id
     */
    public function save() {

        if($this->id) {
        /* Update scope if user id is true */            
            
            $params = array(
                $this->vcImage,
                $this->username,
                $this->password,
                $this->firstname,
                $this->lastname,
                $this->address,
                $this->zipcode,
                $this->city,
                $this->country,
                $this->email,
                $this->phone1,
                $this->phone2,
                $this->phone3,
                $this->created,
                $this->suspended,
                $this->id
            );
            
            /* Unset password if empty */
            if(empty($this->password)) {
                unset($params[2]);
            }
            
            /* Build update sql */
            $sql = "UPDATE user SET " . 
                    "vcImage = ?," . 
                    "username = ?,";
            /* Unset password if empty */
            if(!empty($this->password)) {
                $sql .= "password = ?, ";
            }
            $sql .= "firstname = ?, " . 
                    "lastname = ?, " . 
                    "address = ?, " . 
                    "zipcode = ?, " . 
                    "city = ?, " . 
                    "country = ?, " . 
                    "email = ?, " . 
                    "phone1 = ?, " . 
                    "phone2 = ?, " . 
                    "phone3 = ?, " . 
                    "created = ?, " .
                    "suspended = ? " . 
                    "WHERE id = ?";
            
            $this->db->_query($sql, $params);
            
            return $this->id;
            
        } else {
        /* Create scope if user id is false */
            
            $params = array(
                $this->vcImage,
                $this->username,
                $this->password,
                $this->firstname,
                $this->lastname,
                $this->address,
                $this->zipcode,
                $this->city,
                $this->country,
                $this->email,
                $this->phone1,
                $this->phone2,
                $this->phone3,
                time(),
                $this->suspended
            );
            
            $sql = "INSERT INTO user(" . 
                    "vcImage," . 
                    "username," . 
                    "password, " . 
                    "firstname, " . 
                    "lastname, " . 
                    "address, " . 
                    "zipcode, " . 
                    "city, " . 
                    "country, " . 
                    "email, " . 
                    "phone1, " . 
                    "phone2, " . 
                    "phone3, " . 
                    "created, " .
                    "suspended) " . 
                    "VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"; 
                        
            $this->db->_query($sql, $params);
            
            /* Return new id */
            return $this->db->_getinsertid();
        }
        
    }
    
    /**
     * Mark user as deleted
     * @param int $id
     */
    public function delete($id) {
        $params = array($id);
        $strUpdate = "UPDATE user SET deleted = 1 " . 
                        "WHERE id = ?";
        $this->db->_query($strUpdate, $params);
    }
    
}
