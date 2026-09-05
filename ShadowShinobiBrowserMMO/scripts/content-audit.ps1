param(
    [string]$Root = (Join-Path $PSScriptRoot '..\src\0-August-2018(latest)'),
    [string]$Output = (Join-Path $PSScriptRoot '..\docs\content-audit.csv')
)

$ErrorActionPreference = 'Stop'

if (-not (Test-Path -LiteralPath $Root)) {
    throw "Source root not found: $Root"
}

$patterns = @(
    'Naruto', 'Konoha', 'Suna', 'Sannin', 'Jutsu', 'Chakra',
    'Senjutsu', 'Fukasaku', 'Shima', 'Nine-Tail', 'Kage',
    'ninja', 'aldeia', 'vila', 'miss[aã]o', 'treinamento',
    'jutsu', 'chakra', 'duelo', 'mochila', 'banco', 'pousada',
    'comprar', 'vender', 'cidade', 'jogador', 'jogadores',
    'Voc[êe]', 'N[aã]o', 'Por favor', 'Clique', 'Acessar',
    'Realizar', 'Escolha', 'Erro', 'Sucesso'
)

$regex = '(?i)' + (($patterns | ForEach-Object { [regex]::Escape($_) }) -join '|')

$rows = foreach ($file in Get-ChildItem -LiteralPath $Root -Recurse -File -Include *.php,*.html,*.htm,*.js,*.css,*.txt,*.sql) {
    try {
        $lineNumber = 0
        Get-Content -LiteralPath $file.FullName -Encoding UTF8 | ForEach-Object {
            $lineNumber++
            if ($_ -match $regex) {
                [pscustomobject]@{
                    File       = $file.FullName.Substring((Resolve-Path $Root).Path.Length).TrimStart('\','/')
                    Line       = $lineNumber
                    Category   = if ($_ -match '(?i)Naruto|Konoha|Suna|Sannin|Jutsu|Chakra|Senjutsu|Fukasaku|Shima|Nine-Tail|Kage|ninja') { 'legacy-franchise' } else { 'legacy-language' }
                    Text       = $_.Trim()
                }
            }
        }
    }
    catch {
        Write-Warning "Could not read $($file.FullName): $($_.Exception.Message)"
    }
}

$directory = Split-Path -Parent $Output
if (-not (Test-Path -LiteralPath $directory)) {
    New-Item -ItemType Directory -Path $directory | Out-Null
}

$rows | Sort-Object File, Line | Export-Csv -LiteralPath $Output -NoTypeInformation -Encoding UTF8

$legacyFranchise = @($rows | Where-Object Category -eq 'legacy-franchise').Count
$legacyLanguage = @($rows | Where-Object Category -eq 'legacy-language').Count

Write-Host "Shadow Shinobi content audit"
Write-Host "Root: $Root"
Write-Host "Output: $Output"
Write-Host "Legacy franchise hits: $legacyFranchise"
Write-Host "Legacy language hits: $legacyLanguage"
Write-Host "Total hits: $($rows.Count)"
