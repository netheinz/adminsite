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
        $users = get_object_vars($user);

        $p = new listPresenter($user->arrLabels, $users);
        echo $p->presentdetails();

        include DOCROOT . "/cms/assets/incl/footer.php";
        break;

    case "EDIT";
        break;

    case "SAVE":
        break;

    case "DATA":
        $arrUserData = array();
        $sql = "SELECT * FROM user_data LIMIT 3";
        $row = $db->_fetch_array($sql);
        foreach($row as $key => $arrValues) {
            $arrValues["password"] = password_hash($arrValues["password"], PASSWORD_BCRYPT);
            $arrValues["ord_id"] = 1;
            $arrValues["created"] = time();
            $arrValues["suspended"] = 0;
            $arrValues["deleted"] = 0;
            $arrUserData[] = $arrValues;
        }

        echo "<pre>";
        var_dump($arrUserData);
        echo "</pre>";

        $sql = "INSERT INTO user(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        foreach($arrUserData as $values) {
            var_dump(array_values($values));
            //$db->_query($sql, array_values($values));
        }
        break;

    case "DELETE":
        break;

}
