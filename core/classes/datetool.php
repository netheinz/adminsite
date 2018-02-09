<?php
/**
 * Created by PhpStorm.
 * User: heka
 * Date: 09/02/2018
 * Time: 08.25
 */

class datetool
{
    /**
     * Genererer et timestamp ud fra form dato select værdier
     * Eksempel på kald:
     * $obj->birthdate = datetool::makeStamp("birthdate");
     *
     * @param string $strElm Navnet på dato feltet
     * @return int Returnerer et timestamp
     */
    static function makeStamp($strElm) {
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
}
