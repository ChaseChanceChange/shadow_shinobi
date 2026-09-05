<?php
/**
 * Transitional English + Shadow terminology UI layer.
 *
 * Legacy gameplay and database identifiers remain untouched. This map only
 * changes player-facing wording after templates/pages have been assembled.
 * Keep semantic conversions here rather than renaming engine contracts.
 */
function ui_en($text) {
    static $map = null;
    if ($map === null) {
        $map = array(
            // Legacy Portuguese -> English baseline.
            'Erro' => 'Error', 'Ajuda' => 'Help', 'Concluído' => 'Complete',
            'Indisponível' => 'Unavailable', 'Não Disponível' => 'Not Available',
            'Missão Concluída' => 'Contract Complete', 'Missão' => 'Contract',
            'Completar Missão' => 'Complete Contract', 'Fazer Missão Agora' => 'Accept Contract',
            'Sem Tempo de Espera para essa Missão' => 'No Cooldown for this Contract',
            'Treinamento Concluído' => 'Discipline Complete', 'Treino Concluído' => 'Discipline Complete',
            'Treino não Disponível' => 'Discipline Unavailable',
            'Tempo de Espera Concluído' => 'Cooldown Complete',
            'Procurar Jogador' => 'Find Operative', 'Jogador(es) no Mapa.' => 'Operative(s) in Area.',
            'Jogador' => 'Operative', 'Jogadores' => 'Operatives',
            'Mapa' => 'World Map', 'Opções do Mapa' => 'World Map Options', 'Chat do Mapa' => 'Local Channel',
            'Personagem' => 'Operative Record', 'Personagens' => 'Operatives',
            'Cidade' => 'Settlement', 'Vila' => 'Enclave', 'Explorando' => 'Exploring',
            'Lutando' => 'In Combat', 'Na Cidade' => 'In Settlement',
            'Comprar' => 'Buy', 'Vender' => 'Sell', 'Voltar' => 'Back', 'Sair' => 'Logout',
            'Voltar ao Jogo' => 'Back to Game', 'Rank por Level' => 'Standing by Insight',
            'Rank' => 'Standing', 'Level' => 'Standing', 'Nome' => 'Name',
            'Poder de Ataque' => 'Attack Power', 'Poder de Defesa' => 'Defense Power',
            'Ordenar por Level' => 'Sort by Standing',
            'Ordenar por Poder de Ataque' => 'Sort by Attack Power',
            'Ordenar por Poder de Defesa' => 'Sort by Defense Power',
            'Conta' => 'Account', 'Senha' => 'Password',
            'Nível' => 'Standing', 'Força' => 'Strength', 'Destreza' => 'Dexterity',
            'Agilidade' => 'Agility', 'Sorte' => 'Luck', 'Inteligência' => 'Intelligence',
            'Precisão' => 'Accuracy', 'Determinação' => 'Determination',
            'Ataque' => 'Attack', 'Defesa' => 'Defense', 'Vida' => 'Health',
            'Chakra' => 'Essence', 'Experiência' => 'Insight', 'Recompensa' => 'Reward',
            'Requerimento' => 'Requirement', 'Conclusão' => 'Completion',
            'Local' => 'Location', 'Local de Treino' => 'Discipline Site',
            'Local de Conclusão' => 'Completion Location', 'Bônus' => 'Bonus',
            'Banco' => 'Vault', 'Mochila' => 'Pack', 'Itens' => 'Gear', 'Item' => 'Gear',
            'Habilidade' => 'Art', 'Treinamento' => 'Discipline', 'Graduação' => 'Standing',
            'Elementos' => 'Elements', 'Elemento' => 'Element', 'Fogo' => 'Fire',
            'Água' => 'Water', 'Vento' => 'Wind', 'Terra' => 'Earth', 'Raio' => 'Lightning',
            'Neutro' => 'Neutral', 'Online' => 'Online', 'Offline' => 'Offline',
            'Notícias' => 'News', 'Olá' => 'Hello', 'Aguarde...' => 'Please wait...',
            'Não há mensagem aqui.' => 'There are no messages here.',
            'Não existe nenhum jogador com esse nome.' => 'No operative with that name exists.',
            'Mostrar Dados' => 'Show Details', 'Mostrar/Ocultar Senha' => 'Show/Hide Password',
            'Requerimento:' => 'Requirement:', 'Recompensa:' => 'Reward:',
            'Nível:' => 'Standing:', 'Conclusão:' => 'Completion:', 'Bônus:' => 'Bonus:',
            'Norte' => 'North', 'Sul' => 'South', 'Leste' => 'East', 'Oeste' => 'West',
            'Você chegou em seu destino.' => 'You reached your destination.',
            'Você está explorando o mapa.' => 'You are exploring the world.',
            'Você está em uma batalha.' => 'You are in combat.',
            'Escreva o nome do jogador no campo.' => 'Enter the operative name in the field.',
            'Bem-vindo' => 'Welcome', 'Porcentagem Concluída:' => 'Completion:',
            'Não Obrigatória.' => 'Optional.', 'Obrigatória.' => 'Required.',
            'Sim' => 'Yes', 'Não' => 'No', 'Sucesso' => 'Success',
            'Introdução' => 'Introduction', 'Especialização' => 'Specialization',
            'Especializações' => 'Specializations', 'Níveis de Dificuldade' => 'Difficulty Levels',
            'Jogando' => 'Playing', 'Em uma cidade' => 'In a Settlement',
            'Explorando & Lutando' => 'Exploring & Combat', 'Painel de Status' => 'Status Panel',
            'Itens & Drops' => 'Gear & Recovery', 'Monstros' => 'Threats', 'Jutsus' => 'Arts',
            'Leveis' => 'Standing', 'Créditos' => 'Credits', 'Topo' => 'Top',
            'Dados' => 'Details', 'Nenhum' => 'None', 'Ryou' => 'Coin',
            'Jutsu de Busca' => 'Search Art', 'Senjutsu' => 'Essence Discipline',
            'Treinar Agora' => 'Develop Discipline', 'Treino' => 'Discipline',
            'Restam ' => 'Remaining ', 'Minutos de Espera até o Próximo Treinamento' => 'minutes until the next Discipline',
            'Aquisição da Arte Eremita, o Senjutsu.' => 'Unlock the Essence Discipline.',
            'Ser um Genin.' => 'Hold Initiate Standing.', 'Montanha Myoboku.' => 'Myoboku Range.',
            'Vila da Areia.' => 'Sand Enclave.', 'Byakugan' => 'Ocular Art',
            'Você ainda não adquiriu nenhum treinamento.' => 'You have not acquired any Discipline yet.',
            'pretende treinar algum Jutsu?' => 'intend to develop an Art?',
            'completar treinamentos.' => 'develop your Disciplines.',

            // Existing English wording -> Shadow terminology.
            'Mission Complete' => 'Contract Complete', 'Complete Mission' => 'Complete Contract',
            'Start Mission' => 'Accept Contract', 'Mission' => 'Contract',
            'Training Complete' => 'Discipline Complete', 'Training Unavailable' => 'Discipline Unavailable',
            'Training' => 'Discipline', 'Find Player' => 'Find Operative', 'Find player' => 'Find Operative',
            'Player' => 'Operative', 'Players' => 'Operatives', 'Player(s) on Map.' => 'Operative(s) in Area.',
            'Map Options' => 'World Map Options', 'Map Chat' => 'Local Channel', 'Map' => 'World Map',
            'Character' => 'Operative Record', 'Characters' => 'Operatives', 'Character name' => 'Operative name',
            'Town' => 'Settlement', 'Village' => 'Enclave', 'Fighting' => 'In Combat', 'In Town' => 'In Settlement',
            'Level Rankings' => 'Standing Rankings', 'Level' => 'Standing', 'Rank' => 'Standing',
            'Experience' => 'Insight', 'Chakra' => 'Essence', 'Bank' => 'Vault', 'Backpack' => 'Pack',
            'Items' => 'Gear', 'Item' => 'Gear', 'Equipment' => 'Gear', 'Ability' => 'Art', 'Abilities' => 'Arts',
            'Monsters' => 'Threats', 'Drops' => 'Recovery', 'Drop' => 'Recovery',
            'Enemy' => 'Threat', 'Training location' => 'Discipline Site', 'Required item' => 'Required gear',
            'Search Technique' => 'Search Art', 'Jutsu Ocular' => 'Essence Sight',
            'Exploring & Fighting' => 'Exploring & Combat', 'Playing' => 'Active',
        );
    }
    return strtr($text, $map);
}
?>
