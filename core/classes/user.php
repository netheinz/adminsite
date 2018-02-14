<?php
/**
 * Beskrivelse af user klasse
 * Definerer egenskaber og metoder for brugere i systemet
 *
 * @property int $id Brugers id
 * @property string $username Brugernavn - skal være unikt
 * @property string $password Hashet adgangskode
 * @property string $firstname Fornavn(e)
 * @property string $lastname Efternavn(e)
 * @property string $address Adresse - vej og nummer
 * @property int $city_id Postnummer med reference til tabel city
 * @property int $country_id Land Id med reference til tabel country
 * @property int $org_id Organisation Id med reference til tabel org
 * @property string $email Email adresse
 * @property int $phone1 Telefon 1
 * @property int $phone2 Telefon 2
 * @property int $phone3 Telefon 3
 * @property int $birthdate Fødselsdato som timestamp
 * @property string $gender Køn (m/f)
 * @property int $created Oprettelsestidspunkt
 * @property bool $suspended Markør for suspendering brugers adgang
 * @property bool $deleted Markør for soft delete
 *
 * Relationelle properties
 * @property string $city_name Bynavn
 * @property string $country_name Landenavn
 * @property string $org_name Organisationsnavn
 *
 * @property array $arrFormElements Array med info om felternes data og formtype
 * @property array $arrValues Array med felternes værdier
 * @property array $arrGroups Array med de brugergrupper som brugeren er tilknyttet
 * @property array $arrRoles Array med brugerroller
 */

class user {
    
    /* Class Member Properties*/
    public $id;
    public $username;
    public $password;
    public $firstname;
    public $lastname;
    public $address;
    public $city_id;
    public $country_id;
    public $org_id;
    public $email;
    public $phone1;
    public $phone2;
    public $phone3;
    public $birthdate;
    public $gender;
    public $created;
    public $suspended;
    public $deleted;

    public $city_name;
    public $country_name;
    public $org_name;

    public $arrFormElms;
    public $arrValues;
    public $arrGroups;
    public $arrRoles;

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
                "city_id" => ["select", "Post & By", TRUE, FILTER_SANITIZE_NUMBER_INT, ""],
                "country_id" => ["select", "Land", TRUE, FILTER_SANITIZE_NUMBER_INT, ""],
                "org_id" => ["select", "Organisation", TRUE, FILTER_SANITIZE_NUMBER_INT, ""],
                "email" => ["email", "Email", TRUE, FILTER_VALIDATE_EMAIL, ""],
                "phone1" => ["text", "Telefon 1", FALSE, FILTER_SANITIZE_STRING, ""],
                "phone2" => ["text", "Telefon 2", FALSE, FILTER_SANITIZE_STRING, ""],
                "phone3" => ["text", "Telefon 3", FALSE, FILTER_SANITIZE_STRING, ""],
                "birthdate" => ["date", "Fødselsdato", FALSE, FILTER_SANITIZE_STRING, ""],
                "gender" => ["select", "Køn", TRUE, FILTER_SANITIZE_STRING, ""],
                "created" => ["hidden", "Oprettet", TRUE, FILTER_SANITIZE_STRING, ""],
                "suspended" => ["checkbox", "Suspenderet", FALSE, FILTER_SANITIZE_NUMBER_INT, 1]
            ];

        $this->arrValues = array();
        $this->arrRoles = array();
    }

    /**
     * Class Method GetList
     * @return array Returnerer liste over brugere
     */
    public function getlist() {
        $sql = "SELECT * FROM user " . 
                "WHERE deleted = 0";
        return $this->db->_fetch_array($sql);
    }
    
    /**
     * Class Method GetUser
     * @param int $id
     * Henter en bruger ud fra id og tildeler værdier til class properties
     * Joiner med org, city og country og henter navne på relationer
     */
    public function getuser($id) {
        $this->id = $id;
        $sql = "SELECT u.*, o.name AS org_name, c.name AS country_name " .
                "FROM user u " .
                "JOIN org o " .
                "ON u.org_id = o.id " .
                "JOIN country c " .
                "ON u.country_id = c.id " .
                "JOIN city z " .
                "ON u.city_id = z.id " .
                "AND u.id = ? " .
                "AND u.deleted = 0";
        if($row = $this->db->_fetch_array($sql, array($this->id))) {

            /* Henter bruger relaterede grupper til property arrGroups */
            $this->arrGroups = $this->getgrouprelations();

            /* Udtrækker brugers roller fra arrGroups */
            $this->arrRoles = array_column($this->arrGroups, "role");

            /* Looper datarow og sætter user properties */
            foreach($row[0] as $key => $value) {
                $this->$key = $value;
            }

        }
    }

    /**
     * Class Method Save
     * Id bestemmer om der skal oprettes (id<0) eller opdateres (id>0)
     * Bruger arrFormElements til at lave SQL queries i begge betingelser
     * @return int id
     */
    public function save() {

        /* Sæt array vars for felter og parameter og markers */
        $params = [];
        $fields = [];
        $markers = [];

        /* Update hvis id er større end 0 */
        if($this->id) {
            unset($this->arrFormElms["password"]);

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
            $sql = "INSERT INTO user(".implode(",", $fields).") " .
                            "VALUES(".implode(",",$markers).")";
            /* Eksekver query med array params */
            $this->db->_query($sql, $params);
            
            /* Returner det nye id */
            return $this->db->_getinsertid();
        }
        
    }
    
    /**
     * Updater bruger med en soft delete
     * @param int $id
     */
    public function delete($id) {
        $params = array($id);
        $strUpdate = "UPDATE user SET deleted = 1 " . 
                        "WHERE id = ?";
        $this->db->_query($strUpdate, $params);
    }

    /**
     * Henter brugerrelaterede grupper og returner som et array
     * @return array
     */
    public function getgrouprelations() {
        $strSelect = "SELECT g.id, g.name, g.role " .
                        "FROM usergroup g " .
                        "JOIN usergrouprel x " .
                        "ON x.user_id = ? " .
                        "AND g.id = x.group_id " .
                        "AND g.deleted = 0";
        return $this->db->_fetch_array($strSelect, array($this->id));
    }
    
}
