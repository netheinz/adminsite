<?php
require_once filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/cms/assets/incl/init.php";
$mode = isset($_REQUEST["mode"]) && !empty($_REQUEST["mode"]) ? $_REQUEST["mode"] : "";
$strModuleName = "CMS Forside";

switch (strtoupper($mode)) {
    default:
    case "LIST":
        include DOCROOT . "/cms/assets/incl/header.php";

        /* Inkluder header og sidepanel med titler og navi */
        $arrButtonPanel = [
            //htmltool::linkbutton("Opret ny", "?mode=edit&id=-1")
        ];
        echo textPresenter::presentpanel($strModuleName, "Oversigt", $arrButtonPanel);



        include DOCROOT . "/cms/assets/incl/footer.php";
        break;

}
