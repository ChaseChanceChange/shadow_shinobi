<?php 
/*$var = "10/9/2010";
$var2 = "16:0:0";
$bla = tempojutsu($var, $var2, 120);
echo $bla;*/


if (!function_exists('browser')) {
    /**
     * Legacy user-agent helper used by display() for IE padding quirks.
     * Returns a short browser family label; only the IE branch is special-cased.
     */
    function browser()
    {
        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? (string)$_SERVER['HTTP_USER_AGENT'] : '';
        if ($ua === '') {
            return 'Unknown';
        }
        if (stripos($ua, 'MSIE') !== false || stripos($ua, 'Trident/') !== false) {
            return 'Internet Explorer (MSIE/Compatible)';
        }
        if (stripos($ua, 'Edg/') !== false || stripos($ua, 'Edge/') !== false) {
            return 'Microsoft Edge';
        }
        if (stripos($ua, 'Firefox/') !== false) {
            return 'Mozilla Firefox';
        }
        if (stripos($ua, 'Chrome/') !== false || stripos($ua, 'CriOS/') !== false) {
            return 'Google Chrome';
        }
        if (stripos($ua, 'Safari/') !== false) {
            return 'Safari';
        }
        if (stripos($ua, 'Opera') !== false || stripos($ua, 'OPR/') !== false) {
            return 'Opera';
        }
        return 'Other';
    }
}


if (!function_exists('iconeitemmochila')){
function iconeitemmochila($array, &$img, &$dur){
	$img = "orb_img";
	if (($array[2] ?? null) == 1) {$img = "icon_weapon";}
	if (($array[2] ?? null) == 2) {$img = "icon_armor";}
	if (($array[2] ?? null) == 3) {$img = "icon_shield";}
	if ((($array[2] ?? 0) > 3) && (($array[2] ?? 0) < 7)) {$img = "orb";}
	if (($array[1] ?? null) == "mp") {$img = "potion";}
	if (($array[1] ?? null) == "hp") {$img = "potion";}
	if (($array[1] ?? null) == "bp") {$img = "backpack_pequena";}
	if (($array[1] ?? null) == "dia") {$img = "diamond";}
	if (($array[1] ?? null) == "per") {$img = "parchment";}
	if (($array[1] ?? null) == "hm") {$img = "potion";}
	if (($array[1] ?? null) == "hmt") {$img = "potion";}
	if (($array[1] ?? null) == "tp") {$img = "potion";}
	if (($array[1] ?? null) == "bk") {$img = "book";}
	if (($array[3] ?? null) == "X") {$dur = "INF";}else{$dur = $array[3] ?? "";}
}
}





if (!function_exists('tempojutsu')){
function tempojutsu ($data, $hora, $tempoprapassar){
// data formato : dd/mm/aaaa
//hora formato : hh:mm:ss
//tempo pra passar formato : segundos

//1 dia = 86400 segundos ou 1440 minutos.

//lembrar que a hora padr?o ? -2 horas do brasil.

    // A zero/empty cooldown means the action is immediately available.
    if ($tempoprapassar === null || $tempoprapassar === '' || !is_numeric($tempoprapassar)) {
        return "ok";
    }
    $tempoprapassar = (int)$tempoprapassar;
    if ($tempoprapassar <= 0) {
        return "ok";
    }

    // Legacy quest data may have no previous timestamp on first use.
    // Treat an empty/malformed timestamp as no cooldown rather than allowing
    // PHP 8 string/null arithmetic to throw a TypeError.
    if (!is_string($data) || trim($data) === '' || strcasecmp(trim($data), 'None') === 0) {
        return "ok";
    }
    if (!is_string($hora) || trim($hora) === '' || strcasecmp(trim($hora), 'None') === 0) {
        return "ok";
    }

	$datajutsu = explode("/", $data);
    if (count($datajutsu) < 3 || !is_numeric($datajutsu[0]) || !is_numeric($datajutsu[1]) || !is_numeric($datajutsu[2])) {
        return "ok";
    }
	$today = date("j/n/Y"); 
	$datahoje = explode("/", $today);
	


	
	//quantos anos a frente ? tras ou mesmo.
		$quantosanos = ((int)$datahoje[2] - (int)$datajutsu[2]);
	
	//quantos meses, diferen?a das datas.
		$mesquantos = ((int)$datahoje[1] - (int)$datajutsu[1]);
		$mesquantos += ($quantosanos *12);

		
	//quantos dias
		$quantosdias = ((int)$datahoje[0] - (int)$datajutsu[0]);
		$quantosdias += $mesquantos * 30;

		
	//quantas horas
		$horajutsu = explode(":",$hora);
        if (count($horajutsu) < 2 || !is_numeric($horajutsu[0]) || !is_numeric($horajutsu[1])) {
            return "ok";
        }
		$todayhour = date("H:i:s"); 
		$horaagora = explode(":", $todayhour);
		

		//quantas minutos pra segundos
			$quantosmin = ((int)$horaagora[0] - (int)$horajutsu[0]) * 60;//60 minutos
			$quantosmin += ((int)$horaagora[1] - (int)$horajutsu[1]);
			
		
			
	//adicionando os dias nos minutos...
	$quantosmin += $quantosdias * 1440;
			

	
	if ($quantosmin >= $tempoprapassar) {return "ok";}else{
		$tempoprapassar -= $quantosmin;
		return $tempoprapassar;}	 //se for verdadeiro retorna true, se falso retorna o tempo que ainda falta pra passar.
	
	
}}





















//em segundos...
if (!function_exists('tempopassarsg')){
function tempopassarsg ($data, $hora, $tempoprapassar){
// data formato : dd/mm/aaaa
//hora formato : hh:mm:ss
//tempo pra passar formato : segundos

//1 dia = 86400 segundos ou 1440 minutos.

//lembrar que a hora padr?o ? -2 horas do brasil.


	
	$datajutsu = explode("/", $data);
	$today = date("j/n/Y"); 
	$datahoje = explode("/", $today);
	


	
	//quantos anos a frente ? tras ou mesmo.
		$quantosanos = ($datahoje[2] - $datajutsu[2]);
	
	//quantos meses, diferen?a das datas.
		$mesquantos = ($datahoje[1] - $datajutsu[1]);
		$mesquantos += ($quantosanos *12);

		
	//quantos dias
		$quantosdias = ($datahoje[0] - $datajutsu[0]);
		$quantosdias += $mesquantos * 30;

		
	//quantas horas
		$horajutsu = explode(":",$hora);
		$todayhour = date("H:i:s"); 
		$horaagora = explode(":", $todayhour);
		

		//quantas minutos pra segundos
			$quantosmin = ($horaagora[0] - $horajutsu[0]) * 60;//60 minutos
			$quantosmin += ($horaagora[1] - $horajutsu[1]);
			
		
			
	//adicionando os dias nos minutos...
	$quantosmin += $quantosdias * 1440;
	
	
	//conta
	$quantosseg = $quantosmin*60;
	$quantosseg += ($horaagora[2] - $horajutsu[2]);
			

	
	if ($quantosseg >= $tempoprapassar) {return "0-".$quantosseg;}else{
		$tempoprapassar -= $quantosseg;
		return $tempoprapassar."-".$quantosseg;}	 //se for verdadeiro retorna true, se falso retorna o tempo que ainda falta pra passar.
	
	
}}
























