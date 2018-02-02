<?php
require_once filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/cms/assets/incl/init.php";
$mode = setMode();
$strModuleName = "Brugere";

switch(strtoupper($mode)) {
    /* List Mode */
    case "LIST": 
        $strModuleMode = "Oversigt";
        sysHeader();        
        /* Set array button panel */
        $arrButtonPanel = array();
        $arrButtonPanel[] = getButton("button","Opret ny bruger","getUrl('?mode=edit&iUserID=-1')");
        /* Call static panel with title and button options */
        echo textPresenter::presentpanel($strModuleName,$strModuleMode,$arrButtonPanel);
                
        $user = new user();
    
        /* Array with fields and friendly names for list purposes*/
        $arrColumns = array(
            "opts" => "Options",
            "vcFirstName" => "Fornavn",
            "vcUserName" => "Brugernavn"
        );
        
        /* Array for all user rows */
        $arrUsers = array();
        
        /* List userlist and set editing options */
        foreach($user->getlist() as $key => $arrValues) {
            $arrValues["opts"] = getIcon("?mode=details&iUserID=" . $arrValues["iUserID"], "eye") .             
                                    getIcon("?mode=edit&iUserID=" . $arrValues["iUserID"], "pencil") .             
                                    getIcon("?mode=delete&iUserID=" . $arrValues["iUserID"], "trash");             
            /* Add value row to arrUsers */
            $arrUsers[] = $arrValues;
        }
        
        /* Call list presenter object with columns (arrColumns) and rows (arrUsers) */
        $p = new listPresenter($arrColumns, $arrUsers);
        echo $p->presentlist();
 
        sysFooter();
        break;
    
    case "DETAILS":
        $iUserID = (int)filter_input(INPUT_GET, "iUserID", FILTER_SANITIZE_NUMBER_INT);        
        $strModuleMode = "Detaljer";
        sysHeader();        
        /* Set array button panel */
        $arrButtonPanel = array();
        $arrButtonPanel[] = getButton("button","Rediger","getUrl('?mode=edit&iUserID=" .$iUserID. "')");
        $arrButtonPanel[] = getButton("button","Opret ny","getUrl('?mode=edit&iUserID=-1')");
        $arrButtonPanel[] = getButton("button","Vælg grupper","getUrl('?mode=selectgroups&iUserID=".$iUserID."')");
        $arrButtonPanel[] = getButton("button","Oversigt","getUrl('?mode=list')");
        /* Call static panel with title and button options */
        echo textPresenter::presentpanel($strModuleName,$strModuleMode,$arrButtonPanel);
        
        /* Create class instance and get current user */
        $user = new user();
        $user->getuser($iUserID);
        
        /* Parse object vars into array */
        $arrValues = get_object_vars($user);
        
        /* Unset index with password */
        unset($arrValues["vcPassword"]);
        
        /* Format date value on index daCreated */
        $arrValues["daCreated"] = date2local($arrValues["daCreated"]);
        
        /* Format date value on index daCreated */
        $arrValues["iOrgID"] = $arrValues["vcOrgName"];
        
        /* Call detail presenter object with labels and values */
        $p = new listPresenter($user->arrLabels, $arrValues);
        echo $p->presentdetails();
        
        sysFooter();        
        break;

    case "EDIT";
        $iUserID = (int)filter_input(INPUT_GET, "iUserID", FILTER_SANITIZE_NUMBER_INT);
        $strModuleMode = "Detaljer";
        sysHeader();        
        /* Set array button panel */
        $arrButtonPanel = array();
        $arrButtonPanel[] = getButton("button","Detaljer","getUrl('?mode=details&iUserID=" .$iUserID. "')");
        $arrButtonPanel[] = getButton("button","Opret ny","getUrl('?mode=edit&iUserID=-1')");
        $arrButtonPanel[] = getButton("button","Oversigt","getUrl('?mode=list')");
        /* Call static panel with title and button options */
        echo textPresenter::presentpanel($strModuleName,$strModuleMode,$arrButtonPanel);
        
        /* Create class instance and get current user */
        $user = new user();
        
        /* Get user if state = update */
        if($iUserID > 0) {
            $user->getuser($iUserID);
        } else {
            /* Set password input to required */
            $user->arrFormElms["vcPassword"][2] = TRUE;            
        }
        
        /* Get property values */
        $arrValues = get_object_vars($user);
        
        /* Get orgs */
        $strSelect = "SELECT iOrgID, vcOrgName FROM org WHERE iDeleted = 0 ORDER BY vcOrgName";
        $arrOrgs = $db->_fetch_array($strSelect);
        $arrValues["iOrgID"] = formpresenter::inputSelect("iOrgID", $arrOrgs, $user->iOrgID);
        
        /* Create presenter instance and set form */
        $form = new formpresenter($user->arrLabels, $user->arrFormElms, $arrValues);
        echo $form->presentForm();
        
        sysFooter();
        break;

    case "SAVE":        
        /* Create class instance */
        $user = new user();
        
        /* 
         * Loop form elements from user class & set user property if exists
         * Otherwise set default value from form elements
         * (Defined in user class)
         */
        foreach($user->arrFormElms as $field => $arrTypes) {
            $user->$field = filter_input(INPUT_POST, $field, $arrTypes[1], getDefaultValue($arrTypes[3]));
        }
        
        /* Run md5 hash on password value if not empty */
        if(!empty($user->vcPassword)) { 
            $user->vcPassword = md5($user->vcPassword);
        }
        
        /* Save method*/
        $iUserID = $user->save();
        header("Location: ?mode=details&iUserID=" . $iUserID);
        
        break;
    
    case "DELETE":
        /**
         * Delete mode
         * This mode builds a form for deleting an item
         * The delete action is carried out in the dodelete mode
         */
        $iUserID = (int)filter_input(INPUT_GET, "iUserID", FILTER_SANITIZE_NUMBER_INT);        
        $strModuleMode = "Slet bruger";
        sysHeader();        
        /* Set array button panel */
        $arrButtonPanel = array();
        $arrButtonPanel[] = getButton("button","Oversigt","getUrl('?mode=list')");
        /* Call static panel with title and button options */
        echo textPresenter::presentpanel($strModuleName,$strModuleMode,$arrButtonPanel);
        
        /* Create class instance and get current user */
        $user = new user();
        $user->getuser($iUserID);
        
        /* Show a confirming message */
        $strMessage = "Vil du slette " . $user->vcFirstName . " " . $user->vcLastName . " fra databasen?";
        echo textPresenter::presenttext($strMessage);
        
        /* Slice the form elements array as we only need the top index (iUserID) */
        $arrFormElms = array_slice($user->arrFormElms, 0,1);
        
        /* 
         * Call form presenter 
         * 1. argument (Form labels) = empty
         * 2. argument (Form elements) = hidden input with user id
         * 3. argument (Form values) = the user id to be deleted
         */
        $form = new formpresenter(array(), $arrFormElms, array("iUserID" => $iUserID));
        
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
        $iUserID = (int)filter_input(INPUT_POST, "iUserID", FILTER_VALIDATE_INT);
        
        /* Create class instance */        
        $user = new user();
        
        /* Delete user */
        $user->delete($iUserID);
        
        header("Location: ?mode=list");
        break;
    
    case "SELECTGROUPS":
        /**
         * Mode for selecting user groups
         * This mode uses form presenter to display groups as check box inputs
         * Name and id of check box inputs are listed as arrays with the current id as index
         * Ex: groups[1], groups[3] etc...
         * This way eases the save mode as we can loop through the array to get values
         */
        $iUserID = filter_input(INPUT_GET, "iUserID", FILTER_SANITIZE_NUMBER_INT);
        
        $strModuleMode = "Vælg grupper";
        sysHeader();
        
        /* Set array button panel */
        $arrButtonPanel = array();
        $arrButtonPanel[] = getButton("button","Detaljer","getUrl('?mode=details&iUserID=".$iUserID."')");
        $arrButtonPanel[] = getButton("button","Oversigt","document.location.href='?mode=list'");
        
        /* Call static panel with title and button options */
        echo textPresenter::presentpanel($strModuleName,$strModuleMode,$arrButtonPanel);
        
         /* Create class instance and get current user */
        $user = new user();
        $user->getuser($iUserID);
        
        /* Convert users group array to numeric */
        $user->arrGroups = array_column($user->arrGroups, "iGroupID");
        
        /* Get all groups from group object */
        $group = new usergroup();
        $rows = $group->getlist();

        /* Define arrays for form presenter purposes*/
        $arrLabels = array();
        $arrFormElms = array();
        $arrValues = array();
        
        /* Insert the user id as a hidden valued field */
        $arrFormElms["iUserID"] = array("hidden", FILTER_SANITIZE_NUMBER_INT, FALSE, 0);
        $arrValues["iUserID"] = $iUserID;
        
        /* Loop group rows and build form presenter arrays */
        foreach($rows as $key => $values) {
        
            /* Set index identifier for all arrays */
            $index = "groups[".$values["iGroupID"]."]";
            
            /* Set label array with group name */
            $arrLabels[$index] = $values["vcGroupName"];
            
            /* Set form element array */
            $arrFormElms[$index] = array("checkbox", FILTER_SANITIZE_NUMBER_INT, FALSE, 0);
            
            /* Set usergroup array with user selected groups */
            if(in_array($values["iGroupID"], $user->arrGroups)) {
                $arrValues[$index] = $values["iGroupID"];
            }
        }
        
        /* Create class instance of form presenter */
        $form = new formpresenter($arrLabels, $arrFormElms, $arrValues);
        
        /* Define form action target */
        $form->formAction = "savegroups";
        
        echo $form->presentForm();
        
        break;
        
    case "SAVEGROUPS":
        
        $iUserID = (int)filter_input(INPUT_POST, "iUserID", FILTER_SANITIZE_NUMBER_INT);
                
        /* Delete existing user related groups */
        $params = array($iUserID);
        $strDelete = "DELETE FROM usergrouprel WHERE iUserID = ?";
        $db->_query($strDelete, $params);
        
        /* Create argument array for filtering the post var groups[] */
        $args = array(
                    "groups" => array(
                        "filter" => FILTER_VALIDATE_INT,
                        "flags" => FILTER_REQUIRE_ARRAY
                        )
                    );
        /* Run the filter function with the defined args */
        $arrInputVal = filter_input_array(INPUT_POST, $args);
        
        /* Save user related groups if any */
        if(count($arrInputVal["groups"])) {
            
            /* Convert the filtered array to a numeric indexed array with group id's */
            $arrGroups = array_keys($arrInputVal["groups"]);
            
            /* Loop the numeric array and insert group id with user id in usergrouprel */
            foreach($arrGroups as $value) {
                $params = array($iUserID, $value);
                $strInsert = "INSERT INTO usergrouprel(iUserID, iGroupID) VALUES(?,?)";
                $db->_query($strInsert, $params);
            }
        }
        
        header("Location: ?mode=details&iUserID=" . $iUserID);
        
        break;
}
