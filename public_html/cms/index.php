<?php
require_once filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/cms/assets/incl/init.php";
$mode = isset($_REQUEST["mode"]) && !empty($_REQUEST["mode"]) ? $_REQUEST["mode"] : "";
$strModuleName = "Dummy test af CMS";

switch (strtoupper($mode)) {
    default:
    case "LIST":
        include DOCROOT . "/cms/assets/incl/header.php";

        $arrButtonPanel = [
            htmltool::linkbutton("Opret ny", "?mode=edit&id=-1")
        ];
        echo textPresenter::presentpanel($strModuleName, "Oversigt af dummies", $arrButtonPanel);

        $columns = [
            "options" => "",
            "username" => "Brugernavn",
            "firstname" => "Fornavn",
            "lastname" => "Efternavn",
            "email" => "Email",
            "created" => "Oprettet"
        ];

        $user = new User();

        $users = array();


        foreach ($user->getlist() as $values) {
            $values["options"] = htmltool::linkicon("?mode=edit&id=" . $values["id"], "edit", ["id" => 3]) .
                htmltool::linkicon("?mode=details&id=" . $values["id"], "eye") .
                htmltool::linkicon("?mode=delete&id=" . $values["id"], "trash-alt");

            $values["created"] = htmltool::datetime2local($values["created"]);
            $users[] = $values;
        }

        $p = new listPresenter($columns, $users);
        echo $p->presentlist();


        include DOCROOT . "/cms/assets/incl/footer.php";
        break;

    case "DETAILS":
        /* Sikrer numerisk id fra GET var */
        $id = (int)$_GET["id"];

        include DOCROOT . "/cms/assets/incl/header.php";

        /* Inkluder header og sidepanel med titler og navi */
        $arrButtonPanel = [
            htmltool::linkbutton("Oversigt", "?mode=list")
        ];
        echo textPresenter::presentpanel($strModuleName, "Vis detaljer", $arrButtonPanel);

        /* Kalder user class og henter bruger ud fra id */
        $user = new User();
        $user->getuser($id);

        /* Konverterer timestamp til læsevenligt format */
        $user->created = htmltool::datetime2local($user->created);

        /* Henter class properties med values ud som array */
        $users = get_object_vars($user);

        /* Fjerner brugers password fra array */
        unset($users["password"]);

        /** Bygger array med keys og labels ud fra arrFormElms */
        $array_labels = array_combine(array_keys($user->arrFormElms), array_column($user->arrFormElms, "1"));

        /* Kalder class listpresenter og udskriver detaljer */
        $p = new listPresenter($array_labels, $users);
        echo $p->presentdetails();

        include DOCROOT . "/cms/assets/incl/footer.php";
        break;

    case "EDIT";
        /* Hent Id fra GET var */
        $id = (int)$_GET["id"];

        /* Opretter objektet user ud fra user klassen */
        $user = new User();

        /* Hvis id er større end 0 : rediger ellers opret ny */
        if ($id > 0) {
            $user->getuser($id);
            $strModeName = "Rediger bruger";
        } else {
            $strModeName = "Opret bruger";
        }

        /* Sætter arrValues med user objektets properties og værdier */
        $arrValues = get_object_vars($user);

        /* Inkluder header og sidepanel med titler og navi */
        include DOCROOT . "/cms/assets/incl/header.php";

        /* Definerer modul header med titler og navigation */
        $arrButtonPanel = [
            htmltool::linkbutton("Oversigt", "?mode=list")
        ];
        echo textPresenter::presentpanel($strModuleName, $strModeName, $arrButtonPanel);

        /**
         * Select bokse
         * Select bokse defineres her på modulsiderne med formpresenter metoden inputSelect
         * Metoden tager tre argumenter: name, options og selected value
         * Optionerne defineres som et array
         * Metoden kaldes fra det pågældende felt i arrValues.
         *
         * Eksempel:
         * $arrValues["org_id"] = formpresenter::inputSelect("org_id", $array_orgs, $user->org_id);
         *
         * Eksempel på option udtræk fra en database
         * Array fra db fetch skal modificeres med funktionerne array_combine og array_column
         * Array column returnerer nyt array med værdier fra navngivne index
         * Eks: array_column($row, "id") = array[0] = id, ...
         * Eks: array_column($row, "name") = array[0] = "Karen Jørgensen", ...
         * Combiner de to arrays i users som holder ovenstående struktur
         * Eks: array[1] = "Karen Jørgensen"
         *
         * ### Eksempel ###
         *
         * $sql = "SELECT id, CONCAT(firstname, ' ', lastname) as name FROM user WHERE deleted = 0";
         * $row = $db->_fetch_array($sql);
         * $array_users = array_combine(array_column($row, "id"),array_column($row, "name"));
         *
         * Indsæt en option til standardvisning (Vælg bruger) med array_unshift
         * array_unshift($users, "Vælg bruger");
         * Eksempel på defineret option array
         */
        $array_gender_options = ["m" => "Mand", "k" => "Kvinde"];

        /* Kalder metoden inputSelect med argumenter og assigner output til arrValues */
        $arrValues["gender"] = formpresenter::inputSelect("gender", $array_gender_options, $user->gender);

        /* Kalder form presenter og udskriver formen $$*/
        $p = new formpresenter($user->arrFormElms, $arrValues);
        echo $p->presentForm();

        include DOCROOT . "/cms/assets/incl/footer.php";

        break;

    case "SAVE":
        /*
         * Loop form elements from org class & set org property if exists
         * Otherwise set default value from form elements
         * (Defined in org class)
         */
        $user = new User();

        foreach ($user->arrFormElms as $fieldname => $array_fieldinfo) {
            try {
                $user->$fieldname = filter_input(INPUT_POST, $fieldname, $array_fieldinfo[3]);
            } catch (Exception $e) {
                echo "Fejl: " . $e->getMessage();
            }
        }

        $user->save();
        header("Location: ?mode=details&id=" . $user->id);

        break;

    case "DELETE":
        break;

}
