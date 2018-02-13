<?php

/* 
 * Class usergroup
 */

class Usergroup {
    
    /* Class Member Properties*/
    public $id;
    public $name;
    public $description;
    public $role;
    public $created;
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
                "id" => ["hidden", "ID", FALSE, FILTER_VALIDATE_INT, 0],
                "name" => ["text", "Navn", TRUE, FILTER_SANITIZE_STRING, ""],
                "description" => ["textarea", "Beskrivelse", TRUE, FILTER_SANITIZE_STRING, ""],
                "role" => ["text", "Rolle", TRUE, FILTER_SANITIZE_STRING, ""],
                "created" => ["hidden", "Oprettet", TRUE, FILTER_SANITIZE_STRING, ""]
            ];

        $this->arrValues = array();
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
     * Henter en gruppe ud fra id og tildeler værdier til class properties
     */
    public function get($id) {
        $this->id = $id;
        $sql = "SELECT * " .
                "FROM usergroup " .
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
            $sql = "UPDATE usergroup SET " . implode(" = ?, ", $fields) . " = ? " .
                            "WHERE id = ?";

            /* Eksekver query med array params */
            $this->db->_query($sql, $params);

            /* Returner id */
            return $this->id;
            
        } else {

            /* Opret - sæt created date til nu */
            $this->created = time();

            /* Loop form elements og byg arrays */
            foreach($this->arrFormElms as $name => $array) {
                $params[] = $this->$name;
                $fields[] = $name;
                $markers[] = "?";
            }

            /* Byg SQL kode til insert ud fra array fields og markers */
            $sql = "INSERT INTO usergroup(".implode(",", $fields).") " .
                            "VALUES(".implode(",",$markers).")";
            /* Eksekver query med array params */
            $this->db->_query($sql, $params);
            
            /* Returner det nye id */
            return $this->db->_getinsertid();
        }
        
    }
    
    /**
     * Markerer organisation som slettet (Soft Delete)
     * @param int $id
     */
    public function delete($id) {
        $params = array($id);
        $strUpdate = "UPDATE usergroup SET deleted = 1 " .
                        "WHERE id = ?";
        $this->db->_query($strUpdate, $params);
    }
    
}
