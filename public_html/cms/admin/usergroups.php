<?php
require_once filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/cms/assets/incl/init.php";
$mode = isset($_REQUEST["mode"]) && !empty($_REQUEST["mode"]) ? $_REQUEST["mode"] : "";
$strModuleName = "Brugergrupper";

switch (strtoupper($mode)) {
    default:
    case "LIST":
        include DOCROOT . "/cms/assets/incl/header.php";

        /* Inkluder header og sidepanel med titler og navi */
        $arrButtonPanel = [
            htmltool::linkbutton("Opret ny", "?mode=edit&id=-1")
        ];
        echo textPresenter::presentpanel($strModuleName, "Oversigt", $arrButtonPanel);

        /* Opretter objektet user ud fra org klassen */
        $obj = new Usergroup();

        /* Definerer array til brugerliste */
        $all = array();

        /* Array med felter der skal vises i listen */
        $array_columns_fields = [
            "options", "name", "role", "created"
        ];

        /* Looper array og henter label navn fra arrFormElements */
        $array_column_labels = [];
        $array_column_labels["options"] = "Options";
        foreach($array_columns_fields as $name) {
            if(isset($obj->arrFormElms[$name])) {
                $array_column_labels[$name] = $obj->arrFormElms[$name][1];
            }
        }

        /* Looper listen af organisationer og formaterer data */
        foreach ($obj->getlist() as $values) {
            $values["options"] = htmltool::linkicon("?mode=edit&id=" . $values["id"], "edit", ["id" => 3]) .
                htmltool::linkicon("?mode=details&id=" . $values["id"], "eye");
            if($values["id"] > 4) {
                $values["options"] .= htmltool::linkicon("?mode=delete&id=" . $values["id"], "trash-alt");
            }
            $values["created"] = datetool::datetime2local($values["created"]);
            $all[] = $values;
        }

        /* Kalder listpresenter med labels og brugerliste */
        $p = new listPresenter($array_column_labels, $all);
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
        $obj = new Usergroup();
        $obj->get($id);

        /* Konverterer timestamp til læsevenligt format */
        $obj->created = datetool::datetime2local($obj->created);

        /* Henter class properties med values ud som array */
        $all = get_object_vars($obj);

        /** Bygger array med keys og labels ud fra arrFormElms */
        $array_labels = array_combine(array_keys($obj->arrFormElms), array_column($obj->arrFormElms, "1"));

        /* Kalder class listpresenter og udskriver detaljer */
        $p = new listPresenter($array_labels, $all);
        echo $p->presentdetails();

        include DOCROOT . "/cms/assets/incl/footer.php";
        break;

    case "EDIT";
        /* Hent Id fra GET var */
        $id = (int)$_GET["id"];

        /* Opretter objektet user ud fra user klassen */
        $obj = new Usergroup();

        /* Hvis id er større end 0 : rediger ellers opret ny */
        if ($id > 0) {
            $obj->get($id);
            /* Sætter arrValues med user objektets properties og værdier */
            $arrValues = get_object_vars($obj);

            $strModeName = "Rediger gruppe";
        } else {
            /* Sætter arrValues med user objektets properties og værdier */
            $arrValues = get_object_vars($obj);
            $strModeName = "Opret gruppe";
        }

        /* Inkluder header og sidepanel med titler og navi */
        include DOCROOT . "/cms/assets/incl/header.php";

        /* Definerer modul header med titler og navigation */
        $arrButtonPanel = [
            htmltool::linkbutton("Oversigt", "?mode=list")
        ];
        echo textPresenter::presentpanel($strModuleName, $strModeName, $arrButtonPanel);

        /* Kalder form presenter og udskriver formen */
        $p = new formpresenter($obj->arrFormElms, $arrValues);
        echo $p->presentForm();

        include DOCROOT . "/cms/assets/incl/footer.php";

        break;

    case "SAVE":
        /* Opretter objekt ud fra class */
        $obj = new Usergroup();

        /* Lopper arrFormElements som fieldname og fieldinfo */
        foreach ($obj->arrFormElms as $fieldname => $array_fieldinfo) {
            try {
                /* Sætter class property hvis den eksisterer i post var */
                $obj->$fieldname = filter_input(INPUT_POST, $fieldname, $array_fieldinfo[3]);
            } catch (Exception $e) {
                /* Melder fejl */
                echo "Fejl: " . $e->getMessage();
            }
        }

        /* Gemmer organisation via metoden save() */
        $id = $obj->save();

        /* Header tilbage til detailsside */
        header("Location: ?mode=details&id=" . $id);

        break;

    case "DELETE":
        /* Hent Id fra GET var */
        $id = (int)$_GET["id"];

        /* Opretter objektet ud fra user klassen */
        $obj = new Usergroup();
        $obj->get($id);

        /* Inkluder header og sidepanel med titler og navi */
        include DOCROOT . "/cms/assets/incl/header.php";

        /* Definerer modul header med titler og navigation */
        $arrButtonPanel = [
            htmltool::linkbutton("Oversigt", "?mode=list")
        ];
        echo textPresenter::presentpanel($strModuleName, "Slet organisation", $arrButtonPanel);

        /* Bed admin om at bekræte sletning */
        echo "<p>Vil du slette <i>" . $obj->name . "</i>?</p>\n";

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

        /* Kald objekt og slet hvis id er større end 0 */
        if($id > 0) {
            $obj = new Usergroup();
            $obj->delete($id);
        }

        header("Location: ?mode=list");
        break;

}
