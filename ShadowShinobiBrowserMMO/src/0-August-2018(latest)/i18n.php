<?php
/**
 * Transitional English UI layer.
 * The legacy game stores many Portuguese strings directly in PHP/templates.
 * Keeping this map centralized lets us modernize the wording incrementally
 * without rewriting the gameplay engine in one pass.
 */
function ui_en($text) {
    static $map = null;
    if ($map === null) {
        $map = array(
            'Erro' => 'Error', 'Ajuda' => 'Help', 'Concluído' => 'Complete',
            'Indisponível' => 'Unavailable', 'Não Disponível' => 'Not Available',
            'Missão Concluída' => 'Mission Complete', 'Missão' => 'Mission',
            'Completar Missão' => 'Complete Mission', 'Fazer Missão Agora' => 'Start Mission',
            'Sem Tempo de Espera para essa Missão' => 'No Cooldown for this Mission',
            'Treinamento Concluído' => 'Training Complete', 'Treino Concluído' => 'Training Complete',
            'Treino não Disponível' => 'Training Unavailable',
            'Tempo de Espera Concluído' => 'Cooldown Complete',
            'Procurar Jogador' => 'Find Player', 'Jogador' => 'Player', 'Jogadores' => 'Players',
            'Jogador(es) no Mapa.' => 'Player(s) on Map.', 'Mapa' => 'Map',
            'Opções do Mapa' => 'Map Options', 'Chat do Mapa' => 'Map Chat',
            'Personagem' => 'Character', 'Personagens' => 'Characters',
            'Cidade' => 'Town', 'Vila' => 'Village', 'Explorando' => 'Exploring',
            'Lutando' => 'Fighting', 'Na Cidade' => 'In Town',
            'Comprar' => 'Buy', 'Vender' => 'Sell', 'Voltar' => 'Back', 'Sair' => 'Logout',
            'Conta' => 'Account', 'Nome' => 'Name', 'Senha' => 'Password',
            'Nível' => 'Level', 'Força' => 'Strength', 'Destreza' => 'Dexterity',
            'Agilidade' => 'Agility', 'Sorte' => 'Luck', 'Inteligência' => 'Intelligence',
            'Precisão' => 'Accuracy', 'Determinação' => 'Determination',
            'Ataque' => 'Attack', 'Defesa' => 'Defense', 'Vida' => 'Health',
            'Chakra' => 'Focus', 'Experiência' => 'Experience', 'Recompensa' => 'Reward',
            'Requerimento' => 'Requirement', 'Conclusão' => 'Completion',
            'Local' => 'Location', 'Local de Treino' => 'Training Location',
            'Local de Conclusão' => 'Completion Location', 'Bônus' => 'Bonus',
            'Banco' => 'Bank', 'Mochila' => 'Backpack', 'Itens' => 'Items', 'Item' => 'Item',
            'Habilidade' => 'Ability', 'Treinamento' => 'Training', 'Graduação' => 'Rank',
            'Elementos' => 'Elements', 'Elemento' => 'Element', 'Fogo' => 'Fire',
            'Água' => 'Water', 'Vento' => 'Wind', 'Terra' => 'Earth', 'Raio' => 'Lightning',
            'Neutro' => 'Neutral', 'Online' => 'Online', 'Offline' => 'Offline',
            'Notícias' => 'News', 'Olá' => 'Hello', 'Aguarde...' => 'Please wait...',
            'Não há mensagem aqui.' => 'There are no messages here.',
            'Não existe nenhum jogador com esse nome.' => 'No player with that name exists.',
            'Mostrar Dados' => 'Show Details', 'Mostrar/Ocultar Senha' => 'Show/Hide Password',
            'Requerimento:' => 'Requirement:', 'Recompensa:' => 'Reward:',
            'Nível:' => 'Level:', 'Conclusão:' => 'Completion:', 'Bônus:' => 'Bonus:',
            'Norte' => 'North', 'Sul' => 'South', 'Leste' => 'East', 'Oeste' => 'West',
            'Você chegou em seu destino.' => 'You reached your destination.',
            'Você está explorando o mapa.' => 'You are exploring the map.',
            'Você está em uma batalha.' => 'You are in battle.',
            'Escreva o nome do jogador no campo.' => 'Enter the player name in the field.',
            'Bem-vindo' => 'Welcome', 'Porcentagem Concluída:' => 'Completion:',
            'Não Obrigatória.' => 'Optional.', 'Obrigatória.' => 'Required.',
            'Sim' => 'Yes', 'Não' => 'No', 'Sucesso' => 'Success',
        );
    }
    return strtr($text, $map);
}
?>
