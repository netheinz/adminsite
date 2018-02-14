<?php
/**
 * Beskrivelse af auth klasse
 * Benytter sessionbaseret login og arbejder med to db tabeller: user, usersession
 *
 * @property string $username Brugernavn fra $_POST var
 * @property string $password Adgangskode fra $_POST var
 * @property object $user Bruger Objekt inkl. roller (User Class)
 * @property string $login_html_path Filsti til fil med login form
 * @property string $error_message Variabel til fejl besked
 * @property string $logout Logout Action fra $_GET var
 * @property int $timeoutseconds Antal sekunder bruger kan være passiv inden log ud
 * @property obj $db Property til det globale DB objekt
 */
class Auth {
    public $username;
    public $password;
    public $user;
    public $login_html_path;
    public $error_message;
    public $logout;
    public $timeoutsecs;
    private $db;

    /**
     * Class Constants
     */
    const ISLOGGEDIN = 1;
    const ERROR_NOUSERFOUND = 1;
    const ERROR_NOSESSIONFOUND = 2;
    const ERROR_NOACCESS = 3;

    public function __construct() {
        /**
         * Globaliserer db objekt og tildeler det til klassens db property
         */
        global $db;
        $this->db = $db;

        /**
         * Starter session og sætter dermed et session_id
         */
        session_start();

        /**
         * Sætter post og get variabler
         * Bemærk at username og password har prefixet login_
         */
        $this->username = filter_input(INPUT_POST, "login_username", FILTER_SANITIZE_STRING);
        $this->password = filter_input(INPUT_POST, "login_password", FILTER_SANITIZE_STRING);
        $this->logout = filter_input(INPUT_GET, "action", FILTER_SANITIZE_STRING);

        /**
         * Definerer sti til fil med html til login vindue
         */
        $this->login_html_path = DOCROOT . "/cms/assets/incl/login.php";

        /**
         * Sætter default værdier til fejlbesked og en sessions udløbstid
         */
        $this->error_message = "";
        $this->timeoutsecs = 3600;

        /* Kalder objekt fra brugerklasse */
        $this->user = new User();
    }

    /**
     * Authenticate
     * Metode der betinger om bruger skal logges ud eller logges ind
     */
    public function authenticate() {
        if($this->logout === "logout") {
            /**
             * Kald metode logout hvis logout er sat
             */
            $this->logout();
        }
        if($this->username && $this->password) {
            /**
             * Kald metode initUser hvis brugernavn og password er sat i POST var
             */
            $this->initUser();
        } else {
            if(!$this->getSession()) {
                echo 11;
                /**
                 * Ellers kald metode getSession
                 * Vis loginvindue hvis metoden returnerer false
                 */
                echo $this->loginform();
                exit();
            }
        }
    }

    /**
     * initUser
     * Metode der chekker om bruger eksisterer i user db på username og password
     *
     */
    private function initUser() {
        /**
         * Sætter var params til username fra login form
         */
        $params = array($this->username);

        /**
         * Henter bruger id og password hash i user db ud fra brugernavn
         */
        $strSelectUser = "SELECT id, password " .
                            "FROM user " .
                            "WHERE username = ? " .
                            "AND suspended = 0 " .
                            "AND deleted = 0";

        if($row = $this->db->_fetch_array($strSelectUser, $params)) {
            /**
             * Kald function password_verify med brugers password fra login form og
             * password hash fra user db og tjek om de matcher
             */
            if(password_verify($this->password, $row[0]["password"])) {

                /* Henter bruger på user objekt ud fra user_id */
                $this->user->getuser($row[0]["id"]);

                /**
                 * Indsæt en record i usersession db hvis password matcher med hash
                 * Sæt params med relevante parametre
                 */
                $params = array(
                    session_id(),
                    $this->user->id, //User ID
                    self::ISLOGGEDIN,
                    time(), //CURRENT TIME STAMP
                    time() //CURRENT TIME STAMP
                );

                /* Indsæt row i usersession */
                $strInsertSession = "INSERT INTO usersession (id,user_id,isloggedin, created, lastaction) " .
                                    "VALUES(?,?,?,?,?)";
                $this->db->_query($strInsertSession, $params);

            } else {
                /* Vis login vindue hvis der ikke er et match på password */
                echo $this->loginform(self::ERROR_NOUSERFOUND);
            }
        } else {
            /* Vis login vindue hvis der ikke findes en bruger */
            echo $this->loginform(self::ERROR_NOUSERFOUND);
        }

    }

    /**
     * getSession()
     * Metode til at hente bruger id og sidste handling fra db usersession
     * @return int $user_id Returnerer user_id hvis en session eksisterer
     */
    private function getSession() {

        /* Sætter var params med session() */
        $params = array(session_id());

        /* Henter bruger id og lastaction fra usersession ud fra session_id() */
        $sql = "SELECT user_id, lastaction " .
                "FROM usersession " .
                "WHERE id = ? " .
                "AND isloggedin = 1";

        if($row = $this->db->_fetch_array($sql, $params)) {

            /* Tjek om tid er udløbet hvis der findes en record */
            if($row[0]["lastaction"] > (time() - $this->timeoutsecs)) {

                /* Kald metode updateSession og sæt property user_id til db user_id hvis der findes en row i usersession */
                $this->updateSession();

                /* Henter bruger på user objekt ud fra user_id  og returner true*/
                $this->user->getuser($row[0]["user_id"]);
                return true;

            } else {
                /* Log bruger ud hvis session tiden er udløbet og returner false */
                $this->logout();
                return false;
            }
        }
    }

    /**
     * updateSession
     * Metode der updater lastaction til current timestamp i db usersession
     * hver gang en bruger fortager en handling
     */
    private function updateSession() {
        $params = array(session_id());
        $sql = "UPDATE usersession " .
                "SET lastaction = UNIX_TIMESTAMP() " .
                "WHERE id = ?";
        $this->db->_query($sql, $params);
    }

    /**
     * logout
     * Metode der logger ud: updater isloggedin til 0 i db usersesssion
     * og nulstiller bruger browsersession til nyt session_id()
     */
    public function logout() {
        $params = array(session_id());
        $strSessionUpdate = "UPDATE usersession SET isloggedin = 0 WHERE id = ?";
        $this->db->_query($strSessionUpdate,$params);
        session_unset();
        session_destroy();
        session_start();
        session_regenerate_id();
    }

    /**
     * loginform
     * Metode til at udskrive login form
     * Inkluderer filen i buffer, erstatter makroer (@ERRORMSG@) og returnerer
     * html
     * @param int $error_code Kan tage fejlkoder (Class Constants) og returnere fejlbeskeder
     * @return mixed|string $strBuffer Streng med fuld html dokument af login vindue
     */
    public function loginform($error_code = 0) {
        /* Inkluderer fil i Output Buffer */
        ob_start();
        include_once $this->login_html_path;
        $strBuffer = ob_get_clean();

        /* Erstatter fejl kode med fejl besked ved at kalde metoden getErrorMessage() */
        $strErrorMsg = self::getErrorMessage($error_code);
        $strBuffer = str_replace("@ERRORMSG@", $strErrorMsg, $strBuffer);
        return $strBuffer;
    }

    /**
     * getErrorMessage
     * Metode til at switche fejlkoder til fejlbeskeder
     * @param $int Fejlkode som heltal - Class Constants kan bruges til disse
     * @return string $error_message Fejl besked i human readable format
     */
    private function getErrorMessage($int) {
        switch($int) {
            default:
                $this->error_message = '';
                break;
            case self::ERROR_NOUSERFOUND:
                $this->error_message = 'Brugernavn eller password er forkert.';
                break;
            case self::ERROR_NOSESSIONFOUND:
                $this->error_message = 'Der er ikke sat en session.';
                break;
        }
        return $this->error_message;
    }
}