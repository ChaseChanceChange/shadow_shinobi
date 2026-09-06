<?php // ativarconta.php :: Handles account activation.

include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();




    
    if (isset($_POST["submit"])) {
	
       
        $query = doquery("SELECT * FROM {{table}} WHERE email='".$_POST["mail"]."' LIMIT 1", "users");
        if (mysqli_num_rows($query) != 1) { header("Location: ativarconta.php?conteudo=There is no account registered with that email address."); die();}
        $row = mysqli_fetch_array($query);

		 $updatequery = doquery("UPDATE {{table}} SET verify='1' WHERE email='".$_POST["mail"]."' LIMIT 1", "users");

      
	header("Location: ativarconta.php?conteudo=Your account has been activated successfully."); die(); 
        
    }
	
	$conteudo = $_GET['conteudo'];
	$conteudo = "<font color=brown><center>".strip_tags($conteudo)."</font></center>";
    
    $page = "
	<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/ativarconta.gif\" /></center></td></tr></table>$conteudo
	<form action=\"ativarconta.php\" method=\"post\" id=\"formback\">
	<fieldset id=\"field2\"><legend>Activate Account</legend>
	To activate your account and start playing Shadow Shinobi, fill in the field below:<br><br><center>
	<font color=brown>Keep in mind that activating your account this way isn't recommended. It's always good to have an email on file to recover your password in an emergency. Preferably, activate your account through your email instead.</font></center>
	<br>
	
	
	<center>Enter your email address:<br>
	<input type=\"text\" size=\"40\" name=\"mail\" /><br><br>
	<div class=\"buttons\"><button type=\"submit\" class=\"positive\" name=\"submit\"><img src=\"layoutnovo/dropmenu/b1.gif\" alt=\"\"/> Activate Account</button></div>
	</fieldset></form></center>"
	;
    $title = "Activate Account";
    display($page, $title, false, false, false, false);
    


?>
