<?php
require_once filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/cms/assets/incl/init.php";
$mode = setMode();
$strModuleName = "System Tools";

switch(strtoupper($mode)) {
    case "LIST":
        $strModuleMode = "Adminsite Databases";
        sysHeader();        
        /* Set array button panel */
        $arrButtonPanel = array();
        /* Call static panel with title and button options */
        echo textPresenter::presentpanel($strModuleName,$strModuleMode,$arrButtonPanel);
        
        $txContent = file_get_contents("db.dll");
        echo "<pre>";
        echo $txContent;
        echo "</pre>";
        
        sysFooter();
        break;
    
}