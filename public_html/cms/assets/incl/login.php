<?php
$strCss = isset($strCss) && !empty($strCss) ? $strCss : "bootstrap.min,cms-style";
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CMS LOGIN</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<link href="/cms/assets/css/css.php?f=<?php echo $strCss ?>" rel="stylesheet" type="text/css"/>
</head>
<body id="login">
<div>
   <div id="logo">CMS LOGIN</div>
   <div id="errormsg" class="text-danger">@ERRORMSG@</div>
   <form id="loginform" method="post" autocomplete="off">
       <div class="form-group">
           <div class="input-group">
               <div class="input-group-addon"><span class="fa fa-user"></span></div>
               <input type="text" class="form-control" id="username" name="login_username" autocapitalize="none" placeholder="Indtast brugernavn" value="">
           </div>
       </div>
       <div class="form-group">
           <div class="input-group">
               <div class="input-group-addon"><span class="fa fa-lock"></span></div>
               <input type="password" class="form-control" id="password" name="login_password" placeholder="Indtast password" value="">
           </div>
       </div>
       <div class="form-group">
           <button type="submit" value="Login" class="btn btn-default pull-right">Login</button>
       </div>
   </form>
</div>
<?php
    $strJs = isset($strJs) && !empty($strJs) ? $strJs : "jquery.min,bootstrap.min,summernote.min,modernizr,functions";
?>
<script src="/cms/assets/js/js.php?f=<?php echo $strJs ?>" type="text/javascript"></script>    
<script src="https://use.fontawesome.com/cbcb113789.js"></script>    
    
</body>
</html>