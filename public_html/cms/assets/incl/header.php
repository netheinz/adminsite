<?php
global $auth, $strModuleName, $strModuleMode;
$strCss = isset($strCss) && !empty($strCss) ? $strCss : "bootstrap.min,cms-style,summernote";
$strJs = isset($strJs) && !empty($strJs) ? $strJs : "jquery.min,bootstrap.min,modernizr,summernote.min,validate,functions";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="shortcut icon" href="/cms/assets/images/elma-favo.ico">
    <link href="/cms/assets/css/css.php?f=<?php echo $strCss ?>" rel="stylesheet" type="text/css"/>
    <script src="/cms/assets/js/js.php?f=<?php echo $strJs ?>" type="text/javascript"></script>
    <title>El Mando <?php if(!empty($strModuleName)) { echo ":: " . $strModuleName; } ?></title>
</head>

<body>

    <div class="container-fluid">
        <header>
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="/cms/index.php">Tech College Admin</a>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <div class="container-fluid">
                            <ul class="nav navbar-nav">
                                <li class="dropdown">
                                    <a href="Javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">System <span class="caret"></span></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="/cms/system/index.php">Database Dll</a></li>
                                    </ul>
                                </li>                                
                                <li class="dropdown">
                                    <a href="Javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Administration <span class="caret"></span></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="/cms/admin/config.php">Indstillinger</a></li>
                                        <li class="divider"></li>
                                        <li><a href="/cms/admin/users.php">Brugere</a></li>
                                        <li><a href="/cms/admin/usergroups.php">Brugergrupper</a></li>
                                        <li><a href="/cms/admin/orgs.php">Organisationer</a></li>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a href="Javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Moduler <span class="caret"></span></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="/cms/modules/event.php">Events</a></li>
                                    </ul>
                                </li>                                
                            </ul>
                            <div class="nav navbar-nav navbar-right">
                                <a href="?action=logout" class="hidden-xs">
                                    <button id="logout" class="btn btn-default pull-right" data-original-title="Log af" data-toggle="tooltip" data-placement="bottom"><i class="fas fa-power-off"></i></button>
                                </a>

                                <a href="/cms/index.php?mode=editprofile" class="hidden-xs">
                                    <button id="editprofile" class="btn btn-default pull-right" data-original-title="Rediger profil" data-toggle="tooltip" data-placement="bottom"><i class="fas fa-user"></i></button>
                                </a>
                            </div>

                        </div>
                    </div>                    
                </div>
            </nav>
        </header>
    </div>

    <div class="container-fluid  mainwrapper">
