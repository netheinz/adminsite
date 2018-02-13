<?php
/**
 * Created by PhpStorm.
 * User: heka
 * Date: 09/02/2018
 * Time: 08.25
 */

class datetool
{
    /* Class Properties */
    public $stamp;
    public $format;
    public $name;

    public $useLocalNames;
    public $minuteIntVal;
    private $arrTerms;

    public $accHtml;

    /* Array med dk ugedage */
    public $arrDay2Local = [
        1 => "Søndag", "Mandag", "Tirsdag", "Onsdag", "Torsdag", "Fredag", "Lørdag"
    ];

    /* Array med dk måneder */
    public $arrMonth2Local = [
        1 => "Januar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "September", "Oktober", "November", "December"
    ];

    /* Class Constructor */
    public function __construct($stamp)
    {
        $this->stamp = $stamp;
        $this->name = "";
        $this->format = "";
        $this->useLocalNames = TRUE;
        $this->minuteIntVal = 1;
        $this->accHtml = "";
    }

    /**
     * Method dateSelect
     * Initializes the format and builds select and box options
     * @param string $format
     * @param string $name
     * @return string $accHtml Returns an accumulated html string with select box and options
     */
    public function dateSelect($format, $name) {
        $this->format = $format;
        $this->name = $name;
        /* Initializes $this->arrTerms */
        $this->setFormatTerms();
        /* Sets the selectbox html */
        $this->accHtml = "<select class=\"form-control input-sm dateselect\" name=\"".$this->name . "_" . $this->format ."\">\n";
        /* If set define the minute interval */
        $freq = ($this->format === "minutes") ? $this->minuteIntVal : 1;
        /* Iterates the numFloor and numCeil and builds the select option bundle */
        for($i = $this->arrTerms["numFloor"]; $i <= $this->arrTerms["numCeil"];$i += $freq) {
            /* Get the option text */
            $strOptText = $this->getOptionText($i);
            /* Pad the value with a left zero to fit a match */
            $strSelected = (str_pad($i,2,0,STR_PAD_LEFT) === $this->arrTerms["numSelected"]) ? "selected" : "";
            /* Add option tag to accHtml var */
            $this->accHtml .= "<option value=\"" . $i . "\" " . $strSelected . ">" . $strOptText . "</option>\n";
        }
        $this->accHtml .= "</select>\n";
        return $this->accHtml;
    }

    /**
     * Method getOptsText (Get Option Text)
     * Outputs a user friendly option text for the given format
     * @param var $val The numeric value of a month, hour or minute (Ex: 1, 2, 3 etc... )
     * @return var $val Returns the processed output
     */
    private function getOptionText($val) {
        switch(strtoupper($this->format)) {
            case "MONTH":
                if($this->useLocalNames) {
                    $val = $this->arrMonth2Local[$val];
                } else {
                    $val = date("F", $this->stamp);
                }
                break;
            case "HOURS":
            case "MINUTES":
                $val = str_pad($val,2,0,STR_PAD_LEFT);
                break;
        }
        return $val;
    }

    /**
     * Genererer et timestamp ud fra form dato select værdier
     * Eksempel på kald:
     * $obj->birthdate = datetool::makeStamp("birthdate");
     *
     * @param string $strElm Navnet på dato feltet
     * @return int Returnerer et timestamp
     */
    static function makeStamp($strElm)
    {

        /* Sætter array med de forskellige formater */
        $arrFormats = array("day", "month", "year", "hours", "minutes");

        /* Sætter array til at fange form data */
        $arrDate = array();

        /* Loop datoformater og find dem i form vars ud fra dato feltets navn. Eks: birthdate_day, birthdate_year... */
        foreach($arrFormats as $value) {
            $arrDate[$value] = filter_input(INPUT_POST, $strElm . "_" . $value, FILTER_SANITIZE_NUMBER_INT,
                                    array('options' => array('default' => 0)));
        }

        /* Genererer og returnerer timestamp*/
        return mktime($arrDate["hours"],$arrDate["minutes"],0,$arrDate["month"],$arrDate["day"],$arrDate["year"]);
    }

    /**
     * Method set_format_terms (Initialize terms)
     * Creates an array ($this->arrTerms) with the following:
     * numSelected => the selected date according to the given format (Ex: 5, 12, 2016, 03, 06)
     * numFloor => The minimum number in the format range
     * numCeil => The maximum number in the format range
     * Has no return as this method set the class member propery $this->arrTerms
     */
    public function setFormatTerms() {
        switch(strtoupper($this->format)) {
            case "DAY":
                $this->arrTerms = [
                    "numSelected" => date("d", $this->stamp),
                    "numFloor" => 1,
                    "numCeil" => 31
                ];
                break;
            case "MONTH":
                $this->arrTerms = [
                    "numSelected" => date("m", $this->stamp),
                    "numFloor" => 1,
                    "numCeil" => 12
                ];
                break;
            case "YEAR":
                $this->arrTerms = [
                    "numSelected" => date("Y", $this->stamp),
                    "numFloor" => date("Y", $this->stamp)-100,
                    "numCeil" => date("Y", $this->stamp)+20
                ];
                break;
            case "HOURS":
                $this->arrTerms = [
                    "numSelected" => date("H", $this->stamp),
                    "numFloor" => "00",
                    "numCeil" => 23
                ];
                break;
            case "MINUTES":
                $this->arrTerms = [
                    "numSelected" => date("i", $this->stamp),
                    "numFloor" => "00",
                    "numCeil" => 59
                ];
                break;
        }
    }

    /**
     * @param $date
     * @return string
     */
    static function date2local($date) {
        $dkmonths = array(1 => "Januar",
            "Februar", "Marts", "April", "Maj", "Juni", "Juli",
            "August", "September", "Oktober", "November", "December");
        return date("j", $date) . ". " . $dkmonths[date("n", $date)] . " " . date("Y", $date);
    }

    /**
     * @param $date
     * @return string
     */
    static function datetime2local($date) {
        return self::date2local($date) . " Kl. " . date("H:i", $date);
    }
}