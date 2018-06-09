<?php

/* 
 * Class person
 * Klassefil til personer
 */

class person {
    
    /**
     *  Egenskaber (Class Member Properties)
     * */
    public $id;
    public $name;
    public $email;

    protected $db;

    /**
     * Class Constructor
     * Metode som køres når klassen bliver kaldt
     * Eks: $person = new person();
     * @global object $db
     */
    public function __construct() {

    	/**
	     * Gør $db objektet tilgængeligt i dette scope
	     */
        global $db;

        /**
         * Tildel $db objektet til klassens db egenskab
         * Dermed er objektet tilgængeligt i alle klassens metoder
         */
        $this->db = $db;
    }
    
    /**
     * Class Method GetList
     * @return array Returnerer liste over organisationer
     */
    public function getlist() {
        $sql = "SELECT * FROM usergroup " .
                "WHERE deleted = 0";
        return $this->db->_fetch_array($sql);
    }
    
    /**
     * Class Method GetGroup
     * @param int $id
     * Henter en person ud fra id og tildeler værdier til class properties
     */
    public function get($id) {
        $this->id = $id;
        $sql = "SELECT * " .
                "FROM person " .
                "WHERE id = ?";
        if($row = $this->db->_fetch_array($sql, array($this->id))) {
            foreach($row[0] as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Class Method Create
     * @return int id Returnerer det sidste indsatte id
     */
    public function create() {

	    //Array med property values
	    $params = array( $this->name, $this->email );

	    //SQL INSERT Statement
	    $sql = "INSERT INTO person(name, email) VALUES(?,?)";

	    /* Eksekver query med array params */
	    $this->db->_query( $sql, $params );

	    /* Returner id */

	    return $this->db->_getinsertid();
    }

	/**
	 * Class Method Update
	 * @return int id Returnerer den redigerede rækkes id
	 */
	public function update() {

		//Array med property values
		$params = array( $this->name, $this->email, $this->id );

		//SQL UPDATE Statement
		$sql = "UPDATE person SET " .
		        "name = ?, " .
		        "email = ? " .
		        "WHERE id = ? " .

		/* Eksekver query med array params */
		$this->db->_query( $sql, $params );

		/* Returner id */

		return $this->id;
	}


    /**
     * Sletter person
     * @param int $id
     */
    public function delete($id) {
        $params = array($id);
        $strDelete = "DELETE FROM person SET WHERE id = ?";
        $this->db->_query($strDelete, $params);
    }
    
}
