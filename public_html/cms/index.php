<?php
require_once filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . "/cms/assets/incl/init.php";
$mode = isset($_REQUEST["mode"]) && !empty($_REQUEST["mode"]) ? $_REQUEST["mode"] : "";
$strModuleName = "Dummy test af CMS";

switch(strtoupper($mode)) {
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
            $values["options"] = htmltool::linkicon("?mode=edit&id=".$values["id"], "edit", ["id" => 3]) .
                                    htmltool::linkicon("?mode=details&id=".$values["id"], "eye") .
                                    htmltool::linkicon("?mode=delete&id=".$values["id"], "trash-alt");

            $values["created"] = htmltool::datetime2local($values["created"]);
            $users[] = $values;
        }

        $p = new listPresenter($columns, $users);
        echo $p->presentlist();


        include DOCROOT . "/cms/assets/incl/footer.php";
        break;

    case "DETAILS":
        $id = (int)$_GET["id"];

        include DOCROOT . "/cms/assets/incl/header.php";

        $arrButtonPanel = [
            htmltool::linkbutton("Oversigt", "?mode=list")
        ];
        echo textPresenter::presentpanel($strModuleName, "Vis detaljer", $arrButtonPanel);

        $user = new User();
        $user->getuser($id);
        $user->created = htmltool::datetime2local($user->created);
        $users = get_object_vars($user);
        unset($users["password"]);

        $p = new listPresenter($user->arrLabels, $users);
        echo $p->presentdetails();

        include DOCROOT . "/cms/assets/incl/footer.php";
        break;

    case "EDIT";
        $id = (int)$_GET["id"];

        $user = new User();

        if($id > 0) {
            $user->getuser($id);
            $strModeName = "Rediger bruger";
        } else {
            $strModeName = "Opret bruger";
        }

        /* Get property values */
        $arrValues = get_object_vars($user);

        include DOCROOT . "/cms/assets/incl/header.php";

        $arrButtonPanel = [
            htmltool::linkbutton("Oversigt", "?mode=list")
        ];
        echo textPresenter::presentpanel($strModuleName, $strModeName, $arrButtonPanel);


        $p = new formpresenter($user->arrLabels, $user->arrFormElms, $arrValues);
        echo $p->presentForm();

        include DOCROOT . "/cms/assets/incl/footer.php";

        break;

    case "SAVE":
        break;

    case "DELETE":
        break;

}
