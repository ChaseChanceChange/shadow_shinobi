$ErrorActionPreference = 'Stop'

# Scan the runtime source only. Historical SQL dumps and migration files are
# intentionally outside this audit because they are not player-facing runtime
# content and may contain legacy seed/history data.
$root = Join-Path $PSScriptRoot '..\src\0-August-2018(latest)'
$root = [System.IO.Path]::GetFullPath($root)

$extensions = @('.php', '.html', '.htm', '.js', '.css', '.txt')
$files = Get-ChildItem -LiteralPath $root -Recurse -File |
    Where-Object { $extensions -contains $_.Extension.ToLowerInvariant() }

# These are franchise-specific legacy terms. "Shinobi" is deliberately not
# listed because it is part of the new Shadow Shinobi identity.
$legacyPatterns = @(
    'Naruto', 'Sasuke', 'Sakura', 'Kakashi', 'Itachi', 'Jiraiya', 'Jiraya',
    'Orochimaru', 'Kabuto', 'Sasori', 'Deidara', 'Kisame', 'Gaara', 'Shikamaru',
    'Suigetsu', 'Konan', 'Nagato', 'Pain', 'Pein', 'Akatsuki', 'Uchiha', 'Uzumaki',
    'Hyuga', 'Hyuuga', 'Byakugan', 'Sharingan', 'Senjutsu', 'Genin', 'Chunin',
    'Chuunin', 'Jounin', 'Jōnin', 'Myoboku', 'Nine-Tail', 'Konoha', 'Rasengan',
    'Ninjutsu', 'Genjutsu', 'Jutsu'
)

function Find-Matches($patterns, $category) {
    foreach ($file in $files) {
        $lineNumber = 0
        foreach ($line in Get-Content -LiteralPath $file.FullName -Encoding UTF8) {
            $lineNumber++
            foreach ($pattern in $patterns) {
                # Literal, case-insensitive matching avoids accidental regex
                # interpretation and false negatives from escaped patterns.
                if ($line.IndexOf($pattern, [System.StringComparison]::OrdinalIgnoreCase) -ge 0) {
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

$results = @(Find-Matches $legacyPatterns 'LEGACY')

if ($results.Count -eq 0) {
    Write-Host 'Shadow identity audit: PASS - no configured legacy franchise terms found in runtime source.'
    exit 0
}

$results | Sort-Object Category, File, Line | Format-Table -AutoSize
Write-Host "`nShadow identity audit: $($results.Count) match(es) found in runtime source."
exit 1
