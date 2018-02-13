<?php

class formpresenter {
    /**
     * Class Properties
     */

    public $arrFormElms;
    public $arrValues;

    public $accHtml;

    public $formId;
    public $formMethod;
    public $formAction;
    public $formClass;
    public $iUseEnctype;
    public $arrButtons;

    /**
     * Constructor
     * @param array $arrFormElms
     * @param array $arrValues
     */
    public function __construct($arrFormElms, $arrValues) {
        $this->arrFormElms = $arrFormElms;
        $this->arrValues = $arrValues;

        $this->formId = "adminform";
        $this->formMethod = "POST";
        $this->formAction = "save";
        $this->formClass = "";
        $this->arrButtons = "";

        $this->accHtml = "";
    }

    /**
     * Method PresentForm
     */
    public function presentForm() {

        $this->accHtml = "<form method=\"" . $this->formMethod . "\" class=\"" . $this->formClass .
                                " form-horizontal\" id=\"" . $this->formId . "\">\n";
        $this->accHtml .= "<input type=\"hidden\" name=\"mode\" value=\"" . $this->formAction . "\">\n";

        /**
         * Looper arrFormElms og laver en switch på felt typer
         * Hver felttype har sin egen metode som returnerer feltet i html
         * Synlige felter returneres med metoden inputGroup som wrapper feltets html i div tags
         * array[0] = field type
         * array[1] = field label
         * array[2] = required status (TRUE/FALSE)
         * array[3] = filter_type
         * array[4] = default value
         */
        foreach ($this->arrFormElms as $name => $formelements) {

            switch (strtoupper($formelements[0])) {
                case "HIDDEN":
                    $this->accHtml .= $this->inputHidden($name, $this->arrValues[$name]);
                    break;
                case "TEXT":
                    $strInputHtml = $this->inputText($name, $this->arrValues[$name], $formelements[2]);
                    $this->accHtml .= $this->setInputGroup($name, $formelements[1], $strInputHtml, $formelements[2]);
                    break;
                case "PASSWORD":
                    $strInputHtml = $this->inputPassword($name, $formelements[2]);
                    $this->accHtml .= $this->setInputGroup($name, $formelements[1], $strInputHtml, $formelements[2]);
                    break;
                case "TEXTAREA":
                    $strInput = $this->inputTextarea($name, $this->arrValues[$name], $formelements[2]);
                    $this->accHtml .= $this->setInputGroup($name, $this->arrLabels[$name], $strInput, $formelements[2]);
                    break;
                case "EMAIL":
                    $strInputHtml = $this->inputEmail($name, $this->arrValues[$name], $formelements[2]);
                    $this->accHtml .= $this->setInputGroup($name, $formelements[1], $strInputHtml, $formelements[2]);
                    break;
                case "CHECKBOX":
                    $strInputHtml = $this->inputCheckbox($name, $this->arrValues[$name], $formelements[2]);
                    $this->accHtml .= $this->setInputGroup($name, $formelements[1], $strInputHtml, $formelements[2]);
                    break;
                case "SELECT":
                    $strInputHtml = $this->arrValues[$name];
                    $this->accHtml .= $this->setInputGroup($name, $formelements[1], $strInputHtml, $formelements[2]);
                    break;
                case "DATE":
                    $stamp = ($this->arrValues[$name] > 0) ? $this->arrValues[$name] : time();
                    $d = new datetool($stamp);
                    $strInput = "<div class=\"form-inline\">";
                    $strInput .= $d->dateSelect("day",$name);
                    $strInput .= $d->dateSelect("month",$name);
                    $strInput .= $d->dateSelect("year",$name);
                    $strInput .= "</div>";
                    $this->accHtml .= $this->setInputGroup($name, $formelements[1], $strInput, $formelements[2]);
                    break;
                case "DATETIME":
                    $stamp = ($this->arrValues[$name] > 0) ? $this->arrValues[$name] : time();
                    $d = new datetool($stamp);
                    $strInput = "<div class=\"form-inline\">";
                    $strInput .= $d->dateSelect("day",$name);
                    $strInput .= $d->dateSelect("month",$name);
                    $strInput .= $d->dateSelect("year",$name);
                    $strInput .= $d->dateSelect("hours",$name);
                    $strInput .= $d->dateSelect("minutes",$name);
                    $strInput .= "</div>";
                    $this->accHtml .= $this->setInputGroup($name, $formelements[1], $strInput, $formelements[2]);
                    break;

            }
        }

        /**
         * Tilføjer panel til knapper
         * Definerer Gem og Annuller som standard hvis andet ikke er angivet
         */
        $this->accHtml .= "<div class=\"buttonpanel\">\n\t";
        if (empty($this->arrButtons)) {
            $this->accHtml .= htmltool::button("Annuller", "button") . "\t";
            $this->accHtml .= htmltool::button("Gem");
        } else {
            foreach ($this->arrButtons as $key => $value) {
                $this->accHtml .= $value;
            }
        }

        $this->accHtml .= "</form>\n";
        return $this->accHtml;
    }

    /**
     * Metode til input:hidden
     * @param $name Feltets navn
     * @param $value Feltets værdi
     * @return string Feltet som html
     */
    public function inputHidden($name, $value) {
        return "<input type=\"hidden\" name=\"" . $name . "\" id=\"" . $name . "\" value=\"" . $value . "\">\n";
    }

    /**
     * Metode til input:text
     * @param $name Feltets navn
     * @param $value Feltets værdi
     * @param $required Feltets nødvendighed (required)
     * @return string Feltet som html
     */
    public function inputText($name, $value, $required) {
        return "<input type=\"text\" name=\"" . $name . "\" id=\"" . $name . "\" class=\"form-control\" value=\"" . $value . "\" " . $required . ">\n";
    }

    /**
     * Metode til textarea
     * @param $name Feltets navn
     * @param $value Feltets værdi
     * @param $required Feltets nødvendighed (required)
     * @return string Feltet som html
     */
    public function inputTextarea($name, $value, $required) {
        return "<textarea name=\"" . $name . "\" id=\"" . $name . "\" class=\"form-control\" " . $required . ">".$value."</textarea>\n";
    }


    /**
     * Metode til input:password
     * Passwords er hashet i db og har derfor ingen value da det ikke giver mening
     * at vise det hashede password
     * @param $name Feltets navn
     * @param $required Feltets nødvendighed (required)
     * @return string Feltet som html
     */
    public function inputPassword($name, $required) {
        return "<input type=\"password\" name=\"" . $name . "\" id=\"" . $name . "\" class=\"form-control\" " . $required . ">\n";
    }

    /**
     * Metode til input:email
     * @param $name Feltets navn
     * @param $value Feltets værdi
     * @param $required Feltets nødvendighed (required)
     * @return string Feltet som html
     */
    public function inputEmail($name, $value, $required) {
        return "<input type=\"email\" name=\"" . $name . "\" id=\"" . $name . "\" class=\"form-control\" value=\"" . $value . "\" " . $required . ">\n";
    }

    /**
     * Metode til input:checkbox
     * @param $name Feltets navn
     * @param $value Feltets værdi
     * @param $required Feltets nødvendighed (required)
     * @return string Feltet som html
     */
    public function inputCheckbox($name, $value, $required) {
        $checked = ($name === $value) ? "checked" : "";
        return "<input type=\"checkbox\" name=\"" . $name . "\" id=\"" . $name . "\" ".$checked." class=\"form-control\" value=\"" . $value . "\" " . $required . ">\n";
    }

    /**
     * Metode til select boks
     * Select bokse kaldes fra admin modul siden med navn, options og eksisterende værdi
     * @param string $name Navn på select box
     * @param array $options Array med options - Struktur: array[option_value] = option_text
     * @param int $value Allerede eksisterende værdi til valg af selected option
     * @return string html with selectbox
     */
    static function inputSelect($name, $options, $value) {
        $strHtml = "<select class=\"form-control\" id=\"" . $name . "\" name=\"" . $name . "\">\n";
        foreach ($options as $option_value => $option_text) {
            /* Define if option should be selected */
            $selected = ($option_value === $value) ? "selected" : "";
            /* Accumulate html string with option */
            $strHtml .= "<option value=\"" . $option_value . "\" " . $selected . ">" . $option_text . "</option>\n";
        }
        $strHtml .= "</select>\n";
        return $strHtml;
    }

    /**
     * Metode til at wrappe input felter i html tags
     * @param $fieldname Feltets navn
     * @param $labeltext Lebel tekst
     * @param $strInputHtml html med input element
     * @param $required status for feltets nødvendighed
     * @return string Streng med input og wrapper html
     */
    public function setInputGroup($fieldname, $labeltext, $strInputHtml, $required) {
        $str = "<div class=\"form-group\" data-group=\"" . $fieldname . "\">\n";
        $str .= "  <label class=\"col-sm-3 control-label " . $required . "\" for=\"" . $fieldname . "\">" . $labeltext . ":</label>\n";
        $str .= "  <div class=\"col-sm-9\">\n\t" . $strInputHtml . "  </div>\n";
        $str .= "</div>\n\n";
        return $str;
    }

}
