<?php
require_once filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/cms/assets/incl/init.php";
$mode = setMode();
$strModuleName = "Organisationer";

switch(strtoupper($mode)) {
    /* List Mode */
    case "LIST": 
        $strModuleMode = "Oversigt";
        sysHeader();        
        /* Set array button panel */
        $arrButtonPanel = array();
        $arrButtonPanel[] = getButton("button","Opret ny","getUrl('?mode=edit&iOrgID=-1')");
        /* Call static panel with title and button options */
        echo textPresenter::presentpanel($strModuleName,$strModuleMode,$arrButtonPanel);
                
        $org = new org();
    
        /* Array with fields and friendly names for list purposes*/
        $arrColumns = array(
            "opts" => "Options",
            "vcOrgName" => "Navn"
        );
        
        /* Array for all org rows */
        $arrOrgs = array();
        
        /* List orgs and set editing options */
        foreach($org->getlist() as $key => $arrValues) {
            $arrValues["opts"] = getIcon("?mode=details&iOrgID=" . $arrValues["iOrgID"], "eye") .             
                                    getIcon("?mode=edit&iOrgID=" . $arrValues["iOrgID"], "pencil") .             
                                    getIcon("?mode=delete&iOrgID=" . $arrValues["iOrgID"], "trash");             
            /* Add value row to arrUsers */
            $arrOrgs[] = $arrValues;
        }
        
        /* Call list presenter object with columns (arrColumns) and rows (arrUsers) */
        $p = new listPresenter($arrColumns, $arrOrgs);
        echo $p->presentlist();
 
        sysFooter();
        break;
    
    case "DETAILS":
        $iOrgID = (int)filter_input(INPUT_GET, "iOrgID", FILTER_SANITIZE_NUMBER_INT);        
        $strModuleMode = "Detaljer";
        sysHeader();        
        /* Set array button panel */
        $arrButtonPanel = array();
        $arrButtonPanel[] = getButton("button","Rediger","getUrl('?mode=edit&iOrgID=" .$iOrgID. "')");
        $arrButtonPanel[] = getButton("button","Opret ny","getUrl('?mode=edit&iOrgID=-1')");
        $arrButtonPanel[] = getButton("button","Oversigt","getUrl('?mode=list')");
        /* Call static panel with title and button options */
        echo textPresenter::presentpanel($strModuleName,$strModuleMode,$arrButtonPanel);
        
        /* Get org */
        $org = new org();
        $org->getorg($iOrgID);
        /* Parse object vars into array */
        $arrValues = get_object_vars($org);
        /* Format date value on index daCreated */
        $arrValues["daCreated"] = date2local($arrValues["daCreated"]);
        
        /* Call detail presenter object with labels and values */
        $p = new listPresenter($org->arrLabels, $arrValues);
        echo $p->presentdetails();
        
        sysFooter();        
        break;

    case "EDIT";
        $iOrgID = (int)filter_input(INPUT_GET, "iOrgID", FILTER_SANITIZE_NUMBER_INT);
        $strModuleMode = "Detaljer";
        sysHeader();        
        /* Set array button panel */
        $arrButtonPanel = array();
        $arrButtonPanel[] = getButton("button","Detaljer","getUrl('?mode=details&iOrgID=" .$iOrgID. "')");
        $arrButtonPanel[] = getButton("button","Opret ny","getUrl('?mode=edit&iOrgID=-1')");
        $arrButtonPanel[] = getButton("button","Oversigt","getUrl('?mode=list')");
        /* Call static panel with title and button options */
        echo textPresenter::presentpanel($strModuleName,$strModuleMode,$arrButtonPanel);
        
        /* Create class instance and set current org */
        $org = new org();
        
        /* Get org if state = update */
        if($iOrgID > 0) {
            $org->getorg($iOrgID);
        }
        
        /* Get property values */
        $arrValues = get_object_vars($org);
        
        /* Create presenter instance and set form */
        $form = new formpresenter($org->arrLabels, $org->arrFormElms, $arrValues);
        echo $form->presentForm();
        
        sysFooter();
        break;

    case "SAVE":        
        /* Create class instance */
        $org = new org();
        
        /* 
         * Loop form elements from org class & set org property if exists
         * Otherwise set default value from form elements
         * (Defined in org class)
         */
        foreach($org->arrFormElms as $field => $arrTypes) {
            $org->$field = filter_input(INPUT_POST, $field, $arrTypes[1], getDefaultValue($arrTypes[3]));
        }
        
        /* Save method*/
        $iOrgID = $org->save();
        header("Location: ?mode=details&iOrgID=" . $iOrgID);
        
        break;
    
    case "DELETE":
        /**
         * Delete mode
         * This mode builds a form for deleting an item
         * The delete action is carried out in the dodelete mode
         */
        $iOrgID = (int)filter_input(INPUT_GET, "iOrgID", FILTER_SANITIZE_NUMBER_INT);        
        $strModuleMode = "Slet bruger";
        sysHeader();        
        /* Set array button panel */
        $arrButtonPanel = array();
        $arrButtonPanel[] = getButton("button","Oversigt","getUrl('?mode=list')");
        /* Call static panel with title and button options */
        echo textPresenter::presentpanel($strModuleName,$strModuleMode,$arrButtonPanel);
        
        /* Create class instance and set current org */
        $org = new org();
        $org->getorg($iOrgID);
        
        /* Show a confirming message */
        $strMessage = "Vil du slette " . $org->vcOrgName . " fra databasen?";
        echo textPresenter::presenttext($strMessage);
        
        /* Slice the form elements array as we only need the top index (iOrgID) */
        $arrFormElms = array_slice($org->arrFormElms, 0,1);
        
        /* 
         * Call form presenter 
         * 1. argument (Form labels) = empty
         * 2. argument (Form elements) = hidden input with org id
         * 3. argument (Form values) = the org id to be deleted
         */
        $form = new formpresenter(array(), $arrFormElms, array("iOrgID" => $iOrgID));
        
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
        $iOrgID = (int)filter_input(INPUT_POST, "iOrgID", FILTER_VALIDATE_INT);
        
        /* Create class instance */        
        $org = new org();
        
        /* Delete org */
        $org->delete($iOrgID);
        
        header("Location: ?mode=list");
        break;
}
