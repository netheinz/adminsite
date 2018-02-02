<?php
require_once filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/cms/assets/incl/init.php";
$mode = setMode();
$strModuleName = "Brugergrupper";

switch(strtoupper($mode)) {
    /* List Mode */
    case "LIST": 
        $strModuleMode = "Oversigt";
        sysHeader();        
        /* Set array button panel */
        $arrButtonPanel = array();
        $arrButtonPanel[] = getButton("button","Opret ny","getUrl('?mode=edit&iGroupID=-1')");
        /* Call static panel with title and button options */
        echo textPresenter::presentpanel($strModuleName,$strModuleMode,$arrButtonPanel);
                
        $usergroup = new usergroup();
    
        /* Array with fields and friendly names for list purposes*/
        $arrColumns = array(
            "opts" => "Options",
            "vcGroupName" => "Navn"
        );
        
        /* Array for all usergroup rows */
        $arrGroups = array();
        
        /* List usergroups and set editing options */
        foreach($usergroup->getlist() as $key => $arrValues) {
            $arrValues["opts"] = getIcon("?mode=details&iGroupID=" . $arrValues["iGroupID"], "eye") .             
                                    getIcon("?mode=edit&iGroupID=" . $arrValues["iGroupID"], "pencil") .             
                                    getIcon("?mode=delete&iGroupID=" . $arrValues["iGroupID"], "trash");             
            /* Add value row to arrUsers */
            $arrGroups[] = $arrValues;
        }
        
        /* Call list presenter object with columns (arrColumns) and rows (arrUsers) */
        $p = new listPresenter($arrColumns, $arrGroups);
        echo $p->presentlist();
 
        sysFooter();
        break;
    
    case "DETAILS":
        $iGroupID = (int)filter_input(INPUT_GET, "iGroupID", FILTER_SANITIZE_NUMBER_INT);        
        $strModuleMode = "Detaljer";
        sysHeader();        
        /* Set array button panel */
        $arrButtonPanel = array();
        $arrButtonPanel[] = getButton("button","Rediger","getUrl('?mode=edit&iGroupID=" .$iGroupID. "')");
        $arrButtonPanel[] = getButton("button","Opret ny","getUrl('?mode=edit&iGroupID=-1')");
        $arrButtonPanel[] = getButton("button","Oversigt","getUrl('?mode=list')");
        /* Call static panel with title and button options */
        echo textPresenter::presentpanel($strModuleName,$strModuleMode,$arrButtonPanel);
        
        /* Get usergroup */
        $usergroup = new usergroup();
        $usergroup->getgroup($iGroupID);
        /* Parse object vars into array */
        $arrValues = get_object_vars($usergroup);
        /* Format date value on index daCreated */
        $arrValues["daCreated"] = date2local($arrValues["daCreated"]);
        
        /* Call detail presenter object with labels and values */
        $p = new listPresenter($usergroup->arrLabels, $arrValues);
        echo $p->presentdetails();
        
        sysFooter();        
        break;

    case "EDIT";
        $iGroupID = (int)filter_input(INPUT_GET, "iGroupID", FILTER_SANITIZE_NUMBER_INT);
        $strModuleMode = "Detaljer";
        sysHeader();        
        /* Set array button panel */
        $arrButtonPanel = array();
        $arrButtonPanel[] = getButton("button","Detaljer","getUrl('?mode=details&iGroupID=" .$iGroupID. "')");
        $arrButtonPanel[] = getButton("button","Opret ny","getUrl('?mode=edit&iGroupID=-1')");
        $arrButtonPanel[] = getButton("button","Oversigt","getUrl('?mode=list')");
        /* Call static panel with title and button options */
        echo textPresenter::presentpanel($strModuleName,$strModuleMode,$arrButtonPanel);
        
        /* Create class instance and set current usergroup */
        $usergroup = new usergroup();
        
        /* Get usergroup if state = update */
        if($iGroupID > 0) {
            $usergroup->getgroup($iGroupID);
        }
        
        /* Get property values */
        $arrValues = get_object_vars($usergroup);
        
        /* Create presenter instance and set form */
        $form = new formpresenter($usergroup->arrLabels, $usergroup->arrFormElms, $arrValues);
        echo $form->presentForm();
        
        sysFooter();
        break;

    case "SAVE":        
        /* Create class instance */
        $usergroup = new usergroup();
        
        /* 
         * Loop form elements from usergroup class & set usergroup property if exists
         * Otherwise set default value from form elements
         * (Defined in usergroup class)
         */
        foreach($usergroup->arrFormElms as $field => $arrTypes) {
            $usergroup->$field = filter_input(INPUT_POST, $field, $arrTypes[1], getDefaultValue($arrTypes[3]));
        }
        
        /* Save method*/
        $iGroupID = $usergroup->save();
        header("Location: ?mode=details&iGroupID=" . $iGroupID);
        
        break;
    
    case "DELETE":
        /**
         * Delete mode
         * This mode builds a form for deleting an item
         * The delete action is carried out in the dodelete mode
         */
        $iGroupID = (int)filter_input(INPUT_GET, "iGroupID", FILTER_SANITIZE_NUMBER_INT);        
        $strModuleMode = "Slet brugergruppe";
        sysHeader();        
        /* Set array button panel */
        $arrButtonPanel = array();
        $arrButtonPanel[] = getButton("button","Oversigt","getUrl('?mode=list')");
        /* Call static panel with title and button options */
        echo textPresenter::presentpanel($strModuleName,$strModuleMode,$arrButtonPanel);
        
        /* Create class instance and set current usergroup */
        $usergroup = new usergroup();
        $usergroup->getusergroup($iGroupID);
        
        /* Show a confirming message */
        $strMessage = "Vil du slette " . $usergroup->vcGroupName . " fra databasen?";
        echo textPresenter::presenttext($strMessage);
        
        /* Slice the form elements array as we only need the top index (iGroupID) */
        $arrFormElms = array_slice($usergroup->arrFormElms, 0,1);
        
        /* 
         * Call form presenter 
         * 1. argument (Form labels) = empty
         * 2. argument (Form elements) = hidden input with usergroup id
         * 3. argument (Form values) = the usergroup id to be deleted
         */
        $form = new formpresenter(array(), $arrFormElms, array("iGroupID" => $iGroupID));
        
        /* Overwrite form action (default is save) */
        $form->formAction = "dodelete";
        
        /* Overwrite form buttons (default is Annuller, Gem) */
        $form->arrButtons = array(
            getButton("button","Annuller","goback()") ."\t",
            getButton("submit","Slet", "", "btn-danger")
        );
        
        echo $form->presentForm();
        
        break;  
    
    case "DODELETE":
        /* Get the expected value from POST VAR */
        $iGroupID = (int)filter_input(INPUT_POST, "iGroupID", FILTER_VALIDATE_INT);
        
        /* Create class instance */        
        $usergroup = new usergroup();
        
        /* Delete usergroup */
        $usergroup->delete($iGroupID);
        
        header("Location: ?mode=list");
        break;
}
