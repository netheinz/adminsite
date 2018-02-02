<?php
require_once filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/cms/assets/incl/init.php";
$mode = setMode();
$strModuleName = "Events";

switch(strtoupper($mode)) {
    /* List Mode */
    case "LIST": 
        $strModuleMode = "Oversigt";
        sysHeader();        
        /* Set array button panel */
        $arrButtonPanel = array();
        $arrButtonPanel[] = getButton("button","Opret ny","getUrl('?mode=edit&iEventID=-1')");
        /* Call static panel with title and button options */
        echo textPresenter::presentpanel($strModuleName,$strModuleMode,$arrButtonPanel);
                
        $event = new event();
    
        /* Array with fields and friendly names for list purposes*/
        $arrColumns = array(
            "opts" => "Options",
            "vcTitle" => "Titel",
            "vcOrgName" => "Venue",
            "daStart" => "Starttid",
            "daStop" => "Stoptid",
            "iIsActive" => "Aktiv",
        );
        
        /* Array for all event rows */
        $arrEvents = array();
        
        /* List orgs and set editing options */
        foreach($event->getlist() as $key => $arrValues) {
            $arrValues["opts"] = getIcon("?mode=details&iEventID=" . $arrValues["iEventID"], "eye") .             
                                    getIcon("?mode=edit&iEventID=" . $arrValues["iEventID"], "pencil") .             
                                    getIcon("?mode=delete&iEventID=" . $arrValues["iEventID"], "trash");             
            /* Add value row to arrUsers */
            $arrEvents[] = $arrValues;
        }
        
        /* Call list presenter object with columns (arrColumns) and rows (arrEvents) */
        $p = new listPresenter($arrColumns, $arrEvents);
        echo $p->presentlist();
 
        sysFooter();
        break;
    
    case "DETAILS":
        $iEventID = (int)filter_input(INPUT_GET, "iEventID", FILTER_SANITIZE_NUMBER_INT);        
        $strModuleMode = "Detaljer";
        sysHeader();        
        /* Set array button panel */
        $arrButtonPanel = array();
        $arrButtonPanel[] = getButton("button","Rediger","getUrl('?mode=edit&iEventID=" .$iEventID. "')");
        $arrButtonPanel[] = getButton("button","Opret ny","getUrl('?mode=edit&iEventID=-1')");
        $arrButtonPanel[] = getButton("button","Oversigt","getUrl('?mode=list')");
        /* Call static panel with title and button options */
        echo textPresenter::presentpanel($strModuleName,$strModuleMode,$arrButtonPanel);
        
        /* Get org */
        $event = new event();
        $event->getevent($iEventID);
        /* Parse object vars into array */
        $arrValues = get_object_vars($event);
        
        /* Format date value on date values */
        $arrValues["daCreated"] = date2local($arrValues["daCreated"]);
        $arrValues["daStart"] = time2local($arrValues["daStart"]);
        $arrValues["daStop"] = time2local($arrValues["daStop"]);
        /* */
        $arrValues["iVenueID"] = $arrValues["vcVenueName"];
        
        /* Call detail presenter object with labels and values */
        $p = new listPresenter($event->arrLabels, $arrValues);
        echo $p->presentdetails();
        
        sysFooter();        
        break;

    case "EDIT";
        $iEventID = (int)filter_input(INPUT_GET, "iEventID", FILTER_SANITIZE_NUMBER_INT);
        $strModuleMode = "Detaljer";
        sysHeader();        
        /* Set array button panel */
        $arrButtonPanel = array();
        $arrButtonPanel[] = getButton("button","Detaljer","getUrl('?mode=details&iEventID=" .$iEventID. "')");
        $arrButtonPanel[] = getButton("button","Opret ny","getUrl('?mode=edit&iEventID=-1')");
        $arrButtonPanel[] = getButton("button","Oversigt","getUrl('?mode=list')");
        /* Call static panel with title and button options */
        echo textPresenter::presentpanel($strModuleName,$strModuleMode,$arrButtonPanel);
        
        /* Create class instance and set current org */
        $event = new event();
        
        /* Get org if state = update */
        if($iEventID > 0) {
            $event->getevent($iEventID);
        }
        
        /* Get property values */
        $arrValues = get_object_vars($event);
        
        /* Get orgs as venues */
        $strSelect = "SELECT iOrgID, vcOrgName FROM org WHERE iDeleted = 0 ORDER BY vcOrgName";
        $arrOrgs = $db->_fetch_array($strSelect);
        /* Add a default value to the selectbox */
        array_unshift($arrOrgs, array("iOrgID" => 0, "vcOrgName" => "Vælg venue"));
        
        $arrValues["iVenueID"] = ofrmpresenter::inputSelect("iVenueID", $arrOrgs, $event->iVenueID);
        
        /* Create presenter instance and set form */
        $form = new formpresenter($event->arrLabels, $event->arrFormElms, $arrValues);
        echo $form->presentForm();
        
        sysFooter();
        break;

    case "SAVE":        
        /* Create class instance */
        $event = new event();
        
        /* 
         * Loop form elements from org class & set org property if exists
         * Otherwise set default value from form elements
         * (Defined in org class)
         */
        foreach($event->arrFormElms as $field => $arrTypes) {
            $event->$field = filter_input(INPUT_POST, $field, $arrTypes[1], getDefaultValue($arrTypes[3]));
        }
        
        $event->daStart = makeStamp("daStart");
        $event->daStop = makeStamp("daStop");        
                
        /* Save method*/
        $iEventID = $event->save();
        header("Location: ?mode=details&iEventID=" . $iEventID);
        
        break;
    
    case "DELETE":
        /**
         * Delete mode
         * This mode builds a form for deleting an item
         * The delete action is carried out in the dodelete mode
         */
        $iEventID = (int)filter_input(INPUT_GET, "iEventID", FILTER_SANITIZE_NUMBER_INT);        
        $strModuleMode = "Slet bruger";
        sysHeader();        
        /* Set array button panel */
        $arrButtonPanel = array();
        $arrButtonPanel[] = getButton("button","Oversigt","getUrl('?mode=list')");
        /* Call static panel with title and button options */
        echo textPresenter::presentpanel($strModuleName,$strModuleMode,$arrButtonPanel);
        
        /* Create class instance and set current org */
        $event = new event();
        $event->getevent($iEventID);
        
        /* Show a confirming message */
        $strMessage = "Vil du slette " . $event->vcTitle . " fra databasen?";
        echo textPresenter::presenttext($strMessage);
        
        /* Slice the form elements array as we only need the top index (iEventID) */
        $arrFormElms = array_slice($event->arrFormElms, 0,1);
        
        /* 
         * Call form presenter 
         * 1. argument (Form labels) = empty
         * 2. argument (Form elements) = hidden input with org id
         * 3. argument (Form values) = the org id to be deleted
         */
        $form = new formpresenter(array(), $arrFormElms, array("iEventID" => $iEventID));
        
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
        $iEventID = (int)filter_input(INPUT_POST, "iEventID", FILTER_VALIDATE_INT);
        
        /* Create class instance */        
        $event = new event();
        
        /* Delete org */
        $event->delete($iEventID);
        
        header("Location: ?mode=list");
        break;
}
