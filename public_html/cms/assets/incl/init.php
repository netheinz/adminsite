<?php
/* Global Tools */
define("DOCROOT", filter_input(INPUT_SERVER, "DOCUMENT_ROOT", FILTER_SANITIZE_STRING));
define("COREPATH", substr(DOCROOT, 0, strrpos(DOCROOT,"/")) . "/core/");
require_once COREPATH . 'classes/autoload.php';

/* Classloader - loads class on call from /core/classes/ */
$db = new dbconf();

/* */
$auth = new Auth();
$auth->authenticate();

/* Tjekker om bruger har admin rettigheder */
if(!in_array("admin", $auth->user->arrRoles)) {
    echo $auth->loginform(Auth::ERROR_NOACCESS);
    exit();
}