<?php

class formpresenter {
    /* Class Properties */

    public $arrLabels;
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
     * @param array $arrLabels
     * @param array $arrFormElms
     * @param array $arrValues
     */
    public function __construct($arrLabels, $arrFormElms, $arrValues) {
        $this->arrLabels = $arrLabels;
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

        $this->accHtml = "<form method=\"" . $this->formMethod . "\" class=\"" . $this->formClass . " form-horizontal\" id=\"" . $this->formId . "\">\n";
        $this->accHtml .= "<input type=\"hidden\" name=\"mode\" value=\"" . $this->formAction . "\">\n";

        /**
         * Loops arrFormElms and switches input type
         * Every field type has its own method 
         * array[0] = formtype
         * array[1] = filter_type
         * array[2] = Required Status (TRUE/FALSE)
         */
        foreach ($this->arrFormElms as $key => $array) {
            /* Set if field is required */
            $required = ($array[2] === TRUE) ? "required" : "";

            switch (strtoupper($array[0])) {
                case "HIDDEN":
                    $this->accHtml .= $this->inputHidden($key, $this->arrValues[$key]);
                    break;
                case "TEXT":
                    $strInput = $this->inputText($key, $this->arrValues[$key], $required);
                    $this->accHtml .= $this->setInputGroup($key, $this->arrLabels[$key], $strInput, $required);
                    break;
                case "TEXTAREA":
                    $strInput = $this->inputTextarea($key, $this->arrValues[$key], $required);
                    $this->accHtml .= $this->setInputGroup($key, $this->arrLabels[$key], $strInput, $required);
                    break;
                case "PASSWORD":
                    $strInput = $this->inputPassword($key, $required);
                    $this->accHtml .= $this->setInputGroup($key, $this->arrLabels[$key], $strInput, $required);
                    $strInput = $this->inputPassword("vcPasswordMatch", "", $required);
                    $this->accHtml .= $this->setInputGroup("vcPasswordMatch", "Gentag adgangskode", $strInput, $required);
                    break;
                case "CHECKBOX":
                    $value = isset($this->arrValues[$key]) ? $key : 0;
                    $strInput = $this->inputCheckbox($key, $value, $required);
                    $this->accHtml .= $this->setInputGroup($key, $this->arrLabels[$key], $strInput, $required);
                    break;
                case "SELECT":
                    $strInput = $this->arrValues[$key];
                    $this->accHtml .= $this->setInputGroup($key, $this->arrLabels[$key], $strInput, $required);
                    break;
                case "DATE":
                    $stamp = ($this->arrValues[$key] > 0) ? $this->arrValues[$key] : time();
                    $d = new DateSelector($stamp);                    
                    $strInput = "<div class=\"form-inline\">";
                    $strInput .= $d->dateselect("day",$key);
                    $strInput .= $d->dateselect("month",$key);
                    $strInput .= $d->dateselect("year",$key);
                    $strInput .= "</div>";
                    $this->accHtml .= $this->setInputGroup($key, $this->arrLabels[$key], $strInput, $required);
                    break;
                case "DATETIME":
                    $stamp = ($this->arrValues[$key] > 0) ? $this->arrValues[$key] : time();
                    $d = new DateSelector($stamp);                    
                    $strInput = "<div class=\"form-inline\">";
                    $strInput .= $d->dateselect("day",$key);
                    $strInput .= $d->dateselect("month",$key);
                    $strInput .= $d->dateselect("year",$key);
                    $strInput .= $d->dateselect("hours",$key);
                    $strInput .= $d->dateselect("minutes",$key);
                    $strInput .= "</div>";
                    $this->accHtml .= $this->setInputGroup($key, $this->arrLabels[$key], $strInput, $required);
                    break;
            }
        }

        $this->accHtml .= "<div class=\"buttonpanel\">\n\t";
        if (empty($this->arrButtons)) {
            $this->accHtml .= getButton("button", "Annuller", "goback()") . "\t";
            $this->accHtml .= getButton("submit", "Gem");
        } else {
            foreach ($this->arrButtons as $key => $value) {
                $this->accHtml .= $value;
            }
        }

        $this->accHtml .= "</form>\n";
        return $this->accHtml;
    }

    /* Method inputHidden */

    public function inputHidden($id, $value) {
        return "<input type=\"hidden\" name=\"" . $id . "\" id=\"" . $id . "\" value=\"" . $value . "\">\n";
    }

    /* Method inputText */

    public function inputText($id, $value, $required) {
        return "<input type=\"text\" name=\"" . $id . "\" id=\"" . $id . "\" class=\"form-control\" value=\"" . $value . "\" " . $required . ">\n";
    }

    /* Method inputTextarea */

    public function inputTextarea($id, $value, $required) {
        return "<textarea name=\"" . $id . "\" id=\"" . $id . "\" class=\"form-control\" " . $required . ">".$value."</textarea>\n";
    }

    /* Method inputEmail */

    public function inputEmail($id, $value, $required) {
        return "<input type=\"email\" name=\"" . $id . "\" id=\"" . $id . "\" value=\"" . $value . "\" " . $required . ">\n";
    }

    /* Method inputEmail */

    public function inputCheckbox($id, $value, $required) {
        $checked = ($id === $value) ? "checked" : "";
        return "<input type=\"checkbox\" name=\"" . $id . "\" id=\"" . $id . "\" value=\"1\" " . $required . " " . $checked . ">\n";
    }

    /* Method inputPassword */

    public function inputPassword($id, $required) {
        return "<input type=\"password\" name=\"" . $id . "\" id=\"" . $id . "\" class=\"form-control\" " . $required . ">\n";
    }

    /**
     * Builds a select box
     * Use static function to build select box before form presenter output 
     * @param string $id
     * @param array $options
     * @param int $value
     * @return string html with selectbox
     */
    static function inputSelect($id, $options, $value) {
        $strHtml = "<select class=\"form-control\" id=\"" . $id . "\" name=\"" . $id . "\">\n";
        foreach ($options as $option) {
            /* Convert to array with numeric index */
            $array = array_values($option);
            /* Define if option should be selected */
            $selected = ($value === $array[0]) ? "selected" : "";
            /* Accumulate html string with option */
            $strHtml .= "<option value=\"" . $array[0] . "\" " . $selected . ">" . $array[1] . "</option>\n";
        }
        $strHtml .= "</select>\n";
        return $strHtml;
    }

    /* Method setLabel */

    public function setInputGroup($id, $name, $strInput, $required) {
        $str = "<div class=\"form-group\" data-group=\"" . $id . "\">\n";
        $str .= "  <label class=\"col-sm-3 control-label " . $required . "\" for=\"" . $id . "\">" . $name . ":</label>\n";
        $str .= "  <div class=\"col-sm-9\">\n\t" . $strInput . "  </div>\n";
        $str .= "</div>\n\n";
        return $str;
    }

}
