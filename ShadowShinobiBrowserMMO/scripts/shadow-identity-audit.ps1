$ErrorActionPreference = 'Stop'

$root = Join-Path $PSScriptRoot '..\src\0-August-2018(latest)'
$root = [System.IO.Path]::GetFullPath($root)

$extensions = @('.php', '.html', '.htm', '.js', '.css', '.sql', '.txt', '.md')
$files = Get-ChildItem -LiteralPath $root -Recurse -File | Where-Object { $extensions -contains $_.Extension.ToLowerInvariant() }

$franchisePatterns = @(
    'Naruto', 'Sasuke', 'Sakura', 'Kakashi', 'Itachi', 'Jiraiya', 'Jiraya',
    'Orochimaru', 'Kabuto', 'Sasori', 'Deidara', 'Kisame', 'Gaara', 'Shikamaru',
    'Suigetsu', 'Konan', 'Nagato', 'Pain', 'Pein', 'Akatsuki', 'Uchiha', 'Uzumaki',
    'Hyuga', 'Hyuuga', 'Byakugan', 'Sharingan', 'Senjutsu', 'Kage', 'Genin',
    'Chunin', 'Chuunin', 'Jounin', 'Jōnin', 'Myoboku', 'Nine-Tail', 'Konoha',
    'Shinobi', 'Jutsu'
)

$portuguesePatterns = @(
    'Você', 'voc[eê]', 'não', 'não', 'missão', 'missao', 'jogador', 'jogadores',
    'personagem', 'treinamento', 'treino', 'mochila', 'banco', 'vila', 'cidade',
    'ataque', 'defesa', 'vida', 'experiência', 'experiencia', 'recompensa',
    'requerimento', 'conclusão', 'conclusao', 'nível', 'nivel', 'força', 'forca',
    'destreza', 'agilidade', 'sorte', 'inteligência', 'inteligencia', 'precisão',
    'determinacao', 'determinação', 'água', 'agua', 'vento', 'terra', 'fogo', 'raio',
    'norte', 'sul', 'leste', 'oeste', 'olá', 'ola', 'ajuda', 'sair', 'comprar',
    'vender', 'troca', 'mensagem', 'mapa', 'monstro', 'drop', 'habilidade'
)

function Find-Matches($patterns, $category) {
    foreach ($file in $files) {
        $lineNumber = 0
        foreach ($line in Get-Content -LiteralPath $file.FullName -Encoding UTF8) {
            $lineNumber++
            foreach ($pattern in $patterns) {
                if ($line -match [regex]::Escape($pattern)) {
                    [pscustomobject]@{
                        Category = $category
                        File = $file.FullName.Substring($root.Length + 1)
                        Line = $lineNumber
                        Term = $pattern
                        Text = $line.Trim()
                    }
                    break
                }
            }
        }
    }
}

$results = @(Find-Matches $franchisePatterns 'FRANCHISE') + @(Find-Matches $portuguesePatterns 'PORTUGUESE')

if ($results.Count -eq 0) {
    Write-Host 'Shadow identity audit: PASS - no configured legacy terms found.'
    exit 0
}

$results | Sort-Object Category, File, Line | Format-Table -AutoSize
Write-Host "`nShadow identity audit: $($results.Count) match(es) found."
exit 1
