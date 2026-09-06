<?php // Handles attribute point distribution for the logged-in character.



include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();

// recovery status



if (isset($_GET["do"])) {
    
    $do = $_GET["do"];
    if ($do == "atributos") { atributos(); }
	}





function atributos() {
global $topvar;
$topvar = true;
    /* checking whether the player is logged in */
		//include('cookies.php');
		//$userrow = checkcookies();
		global $userrow;



		if ($userrow == false) { display("Please <a href=\"login.php?do=login\">log in</a> to the game before performing this action.","Error",false,false,false);
		die(); }
		
		//loading attribute variables
		$agilidade = $userrow["agilidade"];
$determinacao = $userrow["determinacao"];
$sorte = $userrow["sorte"];
$precisao = $userrow["precisao"];
$inteligencia = $userrow["inteligencia"];
$pontoatributos = $userrow["pontoatributos"];

//other
	$usuariologadonome = $userrow["charname"];
		
		
		if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("You cannot take any action while in a duel. Click <a href=\"users.php?do=resetarduelo\">here</a> to reset your current duel.","Error",false,false,false);die(); }

					if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=You cannot access this function in the middle of a battle!');die(); }
				
	
		
			
				
	
	
    if (isset($_POST["submit"])) {
        extract($_POST);
		if ($agilidadep == ""){$agilidadep = 0;}
		if ($sortep == ""){$sortep = 0;}
		if ($determinacaop == ""){$determinacaop = 0;}
		if ($precisaop == ""){$precisaop = 0;}
		if ($inteligenciap == ""){$inteligenciap = 0;}
		if (!is_numeric($agilidadep)) { header('Location: ./outroseatributos.php?do=atributos&conteudo=The Agility field must be a number or left blank.');die(); }
		if (!is_numeric($sortep)) { header('Location: ./outroseatributos.php?do=atributos&conteudo=The Luck field must be a number or left blank.');die();}
		if (!is_numeric($determinacaop)) {  header('Location: ./outroseatributos.php?do=atributos&conteudo=The Determination field must be a number or left blank.');die(); }
		if (!is_numeric($precisaop)) { header('Location: ./outroseatributos.php?do=atributos&conteudo=The Precision field must be a number or left blank.');die(); }
		if (!is_numeric($inteligenciap)) { header('Location: ./outroseatributos.php?do=atributos&conteudo=The Intelligence field must be a number or left blank.');die();}
		$agilidadep = floor($agilidadep);
		$sortep = floor($sortep);
		$determinacaop = floor($determinacaop);
		$precisaop = floor($precisaop);
		$inteligenciap = floor($inteligenciap);
		
		
        $pontostotal = $agilidadep + $sortep + $determinacaop + $precisaop + $inteligenciap;

		/*if ($userrow["password"] != md5($oldpass)) { die("The old password you provided was incorrect."); }
        /*$realnewpass = md5($newpass1); */
		if ($pontoatributos == 0) { header('Location: ./outroseatributos.php?do=atributos&conteudo=You have no points available to distribute.');die();}
		if ($pontostotal > $pontoatributos) { header('Location: ./outroseatributos.php?do=atributos&conteudo=You cannot distribute more than '.$pontoatributos.' points.');die();}

		
			
		$pontosrestantes = $pontoatributos - $pontostotal;
			
$determinacao += $determinacaop;
$sorte += $sortep;
$precisao += $precisaop;
$inteligencia += $inteligenciap;
$agilidade += $agilidadep;
				
		$updatequery = doquery("UPDATE {{table}} SET agilidade='$agilidade' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET determinacao='$determinacao' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET precisao='$precisao' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET inteligencia='$inteligencia' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET sorte='$sorte' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET pontoatributos='$pontosrestantes' WHERE charname='$usuariologadonome' LIMIT 1","users");
		
        
				
       header('Location: ./outroseatributos.php?do=atributos&conteudo=Your points have been distributed successfully.');die();
    }
	
	$conteudo = $_GET['conteudo'];
	if ($conteudo != ""){$conteudo = "<center><font color=\"brown\">".strip_tags($conteudo)."</font></center><br>";}
    $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/distribuir.gif\" /></center></td></tr></table>
	$conteudo
	<center><table cellpadding=\"0\" cellspacing=\"0\"><tr><td><table><tr><td colspan=\"2\" bgcolor=\"#452202\"><center><font color=\"white\">My Points</font></center></td></tr>
	<tr bgcolor=\"#E4D094\"><td>Points to Distribute </td><td>$pontoatributos</td></tr>
	<tr bgcolor=\"#FFF1C7\"><td>Agility<img src=\"images/raio.gif\" title=\"Lightning Element\"></td><td>$agilidade</td></tr>
	<tr bgcolor=\"#E4D094\"><td>Luck<img src=\"images/agua.gif\" title=\"Water Element\"></td><td>$sorte</td></tr>
	<tr bgcolor=\"#FFF1C7\"><td>Determination<img src=\"images/fogo.gif\" title=\"Fire Element\"></td><td>$determinacao</td></tr>
	<tr bgcolor=\"#E4D094\"><td>Precision<img src=\"images/vento.gif\" title=\"Wind Element\"></td><td>$precisao</td></tr>
	<tr bgcolor=\"#FFF1C7\"><td>Intelligence<img src=\"images/terra.gif\" title=\"Earth Element\"></td><td>$inteligencia</td></tr>
</table></td>".gettemplate("outroseatributos");
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Return to Game\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Help\" border=\"0\" /></a>";
    display($page, "Distribute Points", false, false, false); 
    
}
















?>
