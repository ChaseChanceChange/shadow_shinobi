<?php // operative-dialogue.php :: Shadow NPC dialogue compatibility handler.

include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();
include('funcoesinclusas.php');

$missaoexplode = explode(",",$userrow["missao"]);

if (($missaoexplode[0] == 6) && ($userrow['latitude'] == -101) && ($userrow['longitude'] == 102)){
    global $valorlib, $indexconteudo;

    $fala = "Welcome, ".$userrow['charname'].". You came looking for knowledge of the Essence Discipline. You found the right operative. Many have tried to master natural energy and failed, but you may eventually gain that control. Train until your Essence is exhausted and you may one day become a true master of the discipline. Carry this information to the Blackleaf Enclave to complete your Contract.";
    $indexconteudo = personagemgeral("$fala", 'personagem5', 'Mila', 'sem<br>');
    $valorlib = 1;
    $updatequery = doquery("UPDATE {{table}} SET missaoswitch='1' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
    include('index.php');
    die();
}

if (($missaoexplode[0] == 9) && ($userrow['latitude'] == 124) && ($userrow['longitude'] == -75)){
    global $valorlib, $indexconteudo;

    $fala = "Welcome, ".$userrow['charname'].". Looking for information about the Stormblade? These blades channel concentrated natural Lightning, making their strikes unusually powerful. The Stormblades can be produced through Refinement by combining another weapon with a rare relic from an old Enclave armory.";
    $indexconteudo = personagemgeral("$fala", 'personagem3', 'Nakima', 'sem<br>');
    $valorlib = 1;
    $updatequery = doquery("UPDATE {{table}} SET missaoswitch='1' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
    include('index.php');
    die();
}

if (($missaoexplode[0] == 12) && ($userrow['latitude'] == 5) && ($userrow['longitude'] == -8)){
    global $valorlib, $indexconteudo;

    $fala = "Welcome, ".$userrow['charname'].". The Enclave needs my help, so I am returning now. Go ahead and tell my brother that I will arrive soon.";
    $indexconteudo = personagemgeral("$fala", 'temari', 'Rin', 'sem<br>');
    $valorlib = 1;
    $updatequery = doquery("UPDATE {{table}} SET missaoswitch='1' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
    include('index.php');
    die();
}

if (($missaoexplode[0] == 15)&& ($userrow['latitude'] == 171) && ($userrow['longitude'] == 171)){
    global $valorlib, $indexconteudo;

    $fala = "Welcome, ".$userrow['charname'].". Have you heard of Refinement? It allows rare materials to be fused into new gear. Some relics carrying the Soul prefix can be combined through the process. Take this knowledge to the Blackleaf Enclave to help complete your Contract.";
    $indexconteudo = personagemgeral("$fala", 'personagem6', 'Shinomori', 'sem<br>');
    $valorlib = 1;
    $updatequery = doquery("UPDATE {{table}} SET missaoswitch='1' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
    include('index.php');
    die();
}

if (($missaoexplode[0] == 18)&& ($userrow['latitude'] == 99) && ($userrow['longitude'] == 26)){
    global $valorlib, $indexconteudo;

    $fala = "Welcome, ".$userrow['charname'].". I heard rumours that the old Warden's staff still exists and that it carries extraordinary power. Return to the Blackleaf Enclave to complete your Contract.";
    $indexconteudo = personagemgeral("$fala", 'personagem9', 'Hikaru', 'sem<br>');
    $valorlib = 1;
    $updatequery = doquery("UPDATE {{table}} SET missaoswitch='1' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
    include('index.php');
    die();
}

if (($missaoexplode[0] == 21)&& ($userrow['latitude'] == 100) && ($userrow['longitude'] == -120)){
    global $valorlib, $indexconteudo;

    $fala = "Welcome, ".$userrow['charname'].". I never felt comfortable in the Mist Enclave; there was too much corruption and too much secrecy. Apparently the local Warden has been looking for me. I will return to learn why. Come back to the Enclave when you are ready to claim your Contract reward.";
    $indexconteudo = personagemgeral("$fala", 'personagem8', 'Mishigan', 'sem<br>');
    $valorlib = 1;
    $updatequery = doquery("UPDATE {{table}} SET missaoswitch='1' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
    include('index.php');
    die();
}

header("Location: index.php");

?>
