<?php
require_once filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/cms/assets/incl/init.php";
$mode = isset($_REQUEST["mode"]) && !empty($_REQUEST["mode"]) ? $_REQUEST["mode"] : "";
$strModuleName = "Dummy test af CMS";

switch (strtoupper($mode)) {
    default:
    case "LIST":
        include DOCROOT . "/cms/assets/incl/header.php";

        /* Inkluder header og sidepanel med titler og navi */
        $arrButtonPanel = [
            htmltool::linkbutton("Opret ny", "?mode=edit&id=-1")
        ];
        echo textPresenter::presentpanel($strModuleName, "Oversigt af dummies", $arrButtonPanel);

        /* Opretter objektet user ud fra user klassen */
        $user = new User();

        /* Definerer array til brugerliste */
        $users = array();

        /* Array med felter der skal vises i listen*/
        $array_columns_fields = [
            "options", "username", "firstname", "lastname", "email", "created"
        ];

        /* Looper array og henter label navn fra arrFormElements */
        $array_column_labels = [];
        $array_column_labels["options"] = "Options";
        foreach($array_columns_fields as $name) {
            if(isset($user->arrFormElms[$name])) {
                $array_column_labels[$name] = $user->arrFormElms[$name][1];
            }
        }

        /* Looper listen af brugere og formaterer data */
        foreach ($user->getlist() as $values) {
            $values["options"] = htmltool::linkicon("?mode=edit&id=" . $values["id"], "edit", ["id" => 3]) .
                htmltool::linkicon("?mode=details&id=" . $values["id"], "eye") .
                htmltool::linkicon("?mode=delete&id=" . $values["id"], "trash-alt");

            $values["created"] = datetool::datetime2local($values["created"]);
            $users[] = $values;
        }

        /* Kalder listpresenter med labels og brugerliste */
        $p = new listPresenter($array_column_labels, $users);
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

        /* Opretter objektet user ud fra user klassen og henter bruger ud fra id */
        $user = new User();
        $user->getuser($id);

        /* Konverterer timestamp til læsevenligt format */
        $user->created = datetool::datetime2local($user->created);
        $user->birthdate = datetool::date2local($user->birthdate);

        /* Sætter øvrige vars til læsevenlige formater */
        $user->gender = ($user->gender === "f") ? "Kvinde" : "Mand";
        $user->suspended = ($user->suspended > 0) ? "Ja" : "Nej";

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
            /* Sætter arrValues med user objektets properties og værdier */
            $arrValues = get_object_vars($user);

            /* Fjerner brugers password fra array */
            unset($arrValues["password"]);
            unset($user->arrFormElms["password"]);

            $strModeName = "Rediger bruger";
        } else {
            /* Sætter arrValues med user objektets properties og værdier */
            $arrValues = get_object_vars($user);
            $strModeName = "Opret bruger";
        }


        /* Inkluder header og sidepanel med titler og navi */
        include DOCROOT . "/cms/assets/incl/header.php";

        /* Definerer modul header med titler og navigation */
        $arrButtonPanel = [
            htmltool::linkbutton("Send nyt password", "?mode=sendnewpwd"),
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
         */

        /* Eksempel på defineret option array */
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

        /* Opretter objektet user ud fra user klassen */
        $user = new User();

        /* Lopper arrFormElements som fieldname og fieldinfo */
        foreach ($user->arrFormElms as $fieldname => $array_fieldinfo) {
            try {
                /* Sætter class property hvis den eksisterer i post var */
                $user->$fieldname = filter_input(INPUT_POST, $fieldname, $array_fieldinfo[3]);
            } catch (Exception $e) {
                /* Melder fejl */
                echo "Fejl: " . $e->getMessage();
            }
        }

        /* Konverterer dato selectbokse til timestamp */
        $user->birthdate = datetool::makeStamp("birthdate");

        /* Gemmer bruger via metoden save() */
        $id = $user->save();

        /* Header tilbage til detailsside */
        header("Location: ?mode=details&id=" . $id);

        break;

    case "DELETE":
        /* Hent Id fra GET var */
        $id = (int)$_GET["id"];

        /* Opretter objektet user ud fra user klassen */
        $user = new User();
        $user->getuser($id);

        /* Inkluder header og sidepanel med titler og navi */
        include DOCROOT . "/cms/assets/incl/header.php";

        /* Definerer modul header med titler og navigation */
        $arrButtonPanel = [
            htmltool::linkbutton("Oversigt", "?mode=list")
        ];
        echo textPresenter::presentpanel($strModuleName, "Slet bruger", $arrButtonPanel);

        /* Bed admin om at bekræte sletning */
        echo "<p>Vil du slette <i>" . $user->firstname . " " . $user->lastname . "</i>?</p>\n";

        /* Sæt arrays til form presenter */
        $arrFormElements = ["id" => ["hidden", "", 1, FILTER_SANITIZE_NUMBER_INT, ""]];
        $arrFormValues = ["id" => $id];

        /* Opret objekt fra formpresenter */
        $f = new formpresenter($arrFormElements, $arrFormValues);

        /* Sæt form action til dodelete */
        $f->formAction = "dodelete";

        /* Sæt form buttons til ok knap */
        $f->arrButtons = [
            htmltool::button("OK")
        ];

        /* Udskriv formpresenter */
        echo $f->presentForm();

        include DOCROOT . "/cms/assets/incl/footer.php";

        break;
    case "DODELETE":

        /* Hent Id fra POST var */
        $id = (int)$_POST["id"];

        /* Kald user objekt og slet bruger hvis id er større end 0 */
        if($id > 0) {
            $user = new User();
            $user->delete($id);
        }

        header("Location: ?mode=list");
        break;

}
