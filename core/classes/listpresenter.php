<?php

class listPresenter {
    public $arrColumns;
    public $arrValues;
    public $accHtml;

    public function __construct($arrColumns, $arrValues) {
        $this->arrColumns = $arrColumns;
        $this->arrValues = $arrValues;
        $this->accHtml = "";
    }
    
    public function presentlist() {
        $this->accHtml = "<div class='table-responsive'>\n";
        $this->accHtml .= "<table class='table-striped table-hover table-list'>\n\t";
        $this->accHtml .= "  <tr>\n";

        foreach($this->arrColumns as $value) {
            $this->accHtml .= "   <th>".$value."</th>\n";
        }

        foreach($this->arrValues as $rows) {
            $this->accHtml .= "  <tr>\n";
            foreach($this->arrColumns as $key => $value) {
                $this->accHtml .= "   <td>" . $rows[$key] . "</td>\n";
            }
            $this->accHtml .= "  </tr>\n";
        }

        $this->accHtml .= "  </tr>\n";
        $this->accHtml .= "</table>\n";

        return $this->accHtml;
    }

    public function presentdetails() {

        $this->accHtml = "<div class=\"table-responsive\">\n"
            . "<table class=\"table-striped table-details ".$this->strListClass." \">\n";
        foreach($this->arrValues as $key => $value) {
            if(isset($this->arrLabels[$key]) && $this->arrLabels[$key]) {
                $this->accHtml .= "</tr>\n";
                $this->accHtml .= "   <td><b>" . $this->arrLabels[$key] . ":</b></td>\n";
                $this->accHtml .= "   <td>" . $value .  "   </td>\n";
                $this->accHtml .= "</tr>\n";
            }
        }

        $this->accHtml .= "</table>\n";
        return $this->accHtml;
    }
}