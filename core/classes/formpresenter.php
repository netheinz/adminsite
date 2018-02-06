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

        $this->accHtml = "<form method=\"" . $this->formMethod . "\" class=\"" . $this->formClass .
                                " form-horizontal\" id=\"" . $this->formId . "\">\n";
        $this->accHtml .= "<input type=\"hidden\" name=\"mode\" value=\"" . $this->formAction . "\">\n";

        /**
         * Loops arrFormElms and switches input type
         * Every field type has its own method 
         * array[0] = formtype
         * array[1] = filter_type
         * array[2] = Required Status (TRUE/FALSE)
         */
        foreach ($this->arrFormElms as $name => $formelements) {

            //echo $key . "=>" . $formelements[0] . ", " . $formelements[2] . "<hr>";

            switch (strtoupper($formelements[0])) {
                case "HIDDEN":
                    $this->accHtml .= $this->inputHidden($name, $this->arrValues[$name]);
                    break;
                case "TEXT":
                    $strInputHtml = $this->inputText($name, $this->arrValues[$name], $formelements[2]);
                    $this->accHtml .= $this->setInputGroup($name, $this->arrLabels[$name], $strInputHtml, $formelements[2]);
                    break;
            }
        }

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

    /* Method inputHidden */

    public function inputHidden($name, $value) {
        return "<input type=\"hidden\" name=\"" . $name . "\" id=\"" . $name . "\" value=\"" . $value . "\">\n";
    }

    /* Method inputText */

    public function inputText($name, $value, $required) {
        return "<input type=\"text\" name=\"" . $name . "\" id=\"" . $name . "\" class=\"form-control\" value=\"" . $value . "\" " . $required . ">\n";
    }

    /* Method setLabel */

    public function setInputGroup($id, $label, $strInput, $required) {
        $str = "<div class=\"form-group\" data-group=\"" . $id . "\">\n";
        $str .= "  <label class=\"col-sm-3 control-label " . $required . "\" for=\"" . $id . "\">" . $label . ":</label>\n";
        $str .= "  <div class=\"col-sm-9\">\n\t" . $strInput . "  </div>\n";
        $str .= "</div>\n\n";
        return $str;
    }

}
