<?php
/* Global Tools */
define("DOCROOT", filter_input(INPUT_SERVER, "DOCUMENT_ROOT", FILTER_SANITIZE_STRING));
define("COREPATH", substr(DOCROOT, 0, strrpos(DOCROOT,"/")) . "/core/");

require_once COREPATH . 'classes/autoload.php';

/* Classloader */
$db = new dbconf();