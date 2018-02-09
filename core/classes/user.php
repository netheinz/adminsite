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

    public $arrFormElms;
    public $arrValues;
    public $arrGroups;

    protected $db;


    /**
     * Class Constructor
     * @global object $db
     */
    public function __construct() {
        global $db;
        $this->db = $db;

        /**
         * Array til form input felter:
         * 1. Index = feltets navn
         *      array[0] = feltets type (hidden, text, textarea, select...)
         *      array[1] = feltets label
         *      array[2] = required - bool der angiver om feltet skal udfyldes eller ej (TRUE/FALSE)
         *      array[3] = filter_type - Filter til rensning af feltet i en request
         *      array[4] = Standard værdi som bruges hvis indhold er tomt
         */
        $this->arrFormElms = [
                "id" => ["hidden", "Bruger ID", FALSE, FILTER_VALIDATE_INT, 0],
                "username" => ["text", "Brugernavn", TRUE, FILTER_SANITIZE_STRING, ""],
                "password" => ["password", "Adgangskode", FALSE, FILTER_SANITIZE_STRING, ""],
                "firstname" => ["text", "Fornavn", TRUE, FILTER_SANITIZE_STRING, ""],
                "lastname" => ["text", "Efternavn", TRUE, FILTER_SANITIZE_STRING, ""],
                "address" => ["text", "Adresse", TRUE, FILTER_SANITIZE_STRING, ""],
                "zipcode" => ["text", "Postnummer", TRUE, FILTER_SANITIZE_STRING, ""],
                "city" => ["text", "By", TRUE, FILTER_SANITIZE_STRING, ""],
                "country" => ["text", "Land", TRUE, FILTER_SANITIZE_STRING, ""],
                "email" => ["email", "Email", TRUE, FILTER_VALIDATE_EMAIL, ""],
                "phone1" => ["text", "Telefon 1", TRUE, FILTER_SANITIZE_STRING, ""],
                "phone2" => ["text", "Telefon 2", TRUE, FILTER_SANITIZE_STRING, ""],
                "phone3" => ["text", "Telefon 3", TRUE, FILTER_SANITIZE_STRING, ""],
                "birthdate" => ["datetime", "Fødselsdato", TRUE, FILTER_SANITIZE_STRING, ""],
                "gender" => ["select", "Køn", TRUE, FILTER_SANITIZE_STRING, ""],
                "created" => ["hidden", "Oprettet", TRUE, FILTER_SANITIZE_STRING, ""],
                "suspended" => ["checkbox", "Suspenderet", TRUE, FILTER_SANITIZE_STRING, 0]
            ];

        $this->arrValues = array();
    }
    
    /**
     * Class Method GetList
     * @return array Returner selected rows as an array
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

        /* Sæt array vars for felter og parameter og markers */
        $params = [];
        $fields = [];
        $markers = [];

        /* Update hvis id er større end 0 */
        if($this->id) {
            /* Loop form elements og byg arrays */
            foreach($this->arrFormElms as $name => $array) {
                $params[] = $this->$name;
                $fields[] = $name;
            }
            /* Flyt index 0 til sidste index på array params */
            array_push($params, $params[0]);
            /* Fjern index 0 på array params */
            array_shift($params);
            /* Fjern index 0 på array fields */
            array_shift($fields);

            /* Byg SQL kode til update ud fra array fields */
            $sql = "UPDATE user SET " . implode(" = ?, ", $fields) . " = ? " .
                            "WHERE id = ?";

            /* Eksekver query med array params */
            $this->db->_query($sql, $params);

            /* Returner id */
            return $this->id;
            
        } else {

            /* Opret - sæt created date til nu */
            $this->created = time();
            /* Hash password */
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);
            /* Sikre 0 værdi på suspended */
            $this->suspended = ($this->suspended) ? $this->suspended : 0;

            /* Loop form elements og byg arrays */
            foreach($this->arrFormElms as $name => $array) {
                $params[] = $this->$name;
                $fields[] = $name;
                $markers[] = "?";
            }

            /* Byg SQL kode til insert ud fra array fields og markers */
            echo $sql = "INSERT INTO user(".implode(",", $fields).") " .
                            "VALUES(".implode(",",$markers).")";
            var_dump($params);
            /* Eksekver query med array params */
            $this->db->_query($sql, $params);
            
            /* Returner det nye id */
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
