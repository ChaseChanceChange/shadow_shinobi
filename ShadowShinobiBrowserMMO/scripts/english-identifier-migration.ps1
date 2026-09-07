# Shadow Shinobi English identifier migration
#
# Renames high-confidence legacy Portuguese PHP identifiers across the active
# game tree while leaving compatibility/output-boundary files untouched.
# Run from the repository root after pulling the branch.

[CmdletBinding(SupportsShouldProcess)]
param(
    [switch]$WhatIfOnly
)

Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'

$repoRoot = (Resolve-Path (Join-Path $PSScriptRoot '..')).Path
$sourceRoot = Join-Path $repoRoot 'src/0-August-2018(latest)'

$excludedFiles = @(
    'legacy_compat.php',
    'legacy_player_cleanup.php',
    'player_language.php',
    'personagemgeral.php',
    'operative_dialogue_helper.php'
)

# Only names with clear semantic equivalence are migrated here. Database column
# names and route/storage values are intentionally excluded from this pass.
$identifierMap = [ordered]@{
    'personagemgeral'   = 'renderOperativeDialogue'
    'tempojutsu'        = 'calculateCooldown'
    'tempopassarsg'     = 'calculateElapsedSeconds'
    'iconeitemmochila'  = 'resolvePackItemIcon'
}

if (-not (Test-Path -LiteralPath $sourceRoot)) {
    throw "Active source tree not found: $sourceRoot"
}

$files = Get-ChildItem -LiteralPath $sourceRoot -Recurse -File -Filter '*.php' |
    Where-Object { $excludedFiles -notcontains $_.Name }

$totalReplacements = 0
$changedFiles = New-Object System.Collections.Generic.List[string]

foreach ($file in $files) {
    $text = Get-Content -LiteralPath $file.FullName -Raw -Encoding UTF8
    $original = $text
    $fileReplacements = 0

    foreach ($legacyName in $identifierMap.Keys) {
        $canonicalName = $identifierMap[$legacyName]
        $pattern = '(?<![A-Za-z0-9_])' + [regex]::Escape($legacyName) + '(?![A-Za-z0-9_])'
        $matches = [regex]::Matches($text, $pattern)
        if ($matches.Count -gt 0) {
            $text = [regex]::Replace($text, $pattern, $canonicalName)
            $fileReplacements += $matches.Count
        }
    }

    if ($fileReplacements -eq 0) {
        continue
    }

    if ($WhatIfOnly) {
        Write-Host ("WOULD UPDATE {0} ({1} replacements)" -f $file.FullName, $fileReplacements)
    } elseif ($PSCmdlet.ShouldProcess($file.FullName, 'Apply English identifier migration')) {
        [System.IO.File]::WriteAllText($file.FullName, $text, (New-Object System.Text.UTF8Encoding($false)))
        Write-Host ("UPDATED {0} ({1} replacements)" -f $file.FullName, $fileReplacements)
    }

    $totalReplacements += $fileReplacements
    $changedFiles.Add($file.FullName)
}

Write-Host ''
Write-Host 'Shadow Shinobi English identifier migration complete.'
Write-Host ("Files affected: {0}" -f $changedFiles.Count)
Write-Host ("Identifier replacements: {0}" -f $totalReplacements)

Write-Host ''
Write-Host 'Canonical names:'
foreach ($legacyName in $identifierMap.Keys) {
    Write-Host ("  {0} -> {1}" -f $legacyName, $identifierMap[$legacyName])
}
