<#
.SYNOPSIS
    Shadow Shinobi content audit. Read-only scan of game source for legacy/franchise
    strings and likely-untranslated (Portuguese) text, so they can be queued for
    retheme/translation batches.

.DESCRIPTION
    Does NOT modify any source file. Writes results to docs/content-audit.csv.

.USAGE
    From the "ShadowShinobiBrowserMMO" project folder:
        pwsh ./scripts/content-audit.ps1
    or on Windows PowerShell:
        powershell -File .\scripts\content-audit.ps1
#>

param(
    [string]$SourceRoot = (Join-Path $PSScriptRoot "..\src"),
    [string]$OutputCsv  = (Join-Path $PSScriptRoot "..\docs\content-audit.csv")
)

# Franchise-specific terms that should not appear in shipped player-facing text.
# Intentionally includes character/place/jutsu names from the original IP so every
# occurrence surfaces for review and retheme-mapping.
$FranchiseTerms = @(
    'naruto','jutsu','chakra','hokage','kage','sharingan','byakugan','rinnegan',
    'akatsuki','konoha','sasuke','sakura','kakashi','jiraiya','tsunade','orochimaru',
    'itachi','madara','obito','nagato','shikamaru','gaara','hinata','neji','tenten',
    'kunai','shuriken','genin','chunin','ch[uú]nin','jounin','j[oō]nin','anbu','sannin',
    'biju','tailed beast','rasengan','chidori','sage mode','senjutsu','doujutsu',
    'uchiha','uzumaki','hyuga','nara','akimichi','yamanaka','inuzuka','aburame'
)

# Heuristic only: Portuguese-only accented characters / common function words that
# usually indicate content still awaiting translation. Expect false positives —
# this is a triage aid, not an authoritative translation list.
$PortugueseHeuristics = @(
    '[áàâãéêíóôõúç]',
    '\bvoc[eê]\b','\bjogador\b','\bmiss[aã]o\b','\bcidade\b','\bn[ií]vel\b',
    '\bataque\b','\bdefesa\b','\business\b','\busu[aá]rio\b','\bsenha\b','\berro\b',
    '\bpersonagem\b','\bvida\b'
)

$extensions = @('*.php','*.html','*.htm','*.css','*.js')

if (-not (Test-Path $SourceRoot)) {
    Write-Error "Source root not found: $SourceRoot"
    exit 1
}

$results = New-Object System.Collections.Generic.List[object]
$files = Get-ChildItem -Path $SourceRoot -Recurse -Include $extensions -File

foreach ($file in $files) {
    $lines = Get-Content -LiteralPath $file.FullName -Encoding UTF8
    for ($i = 0; $i -lt $lines.Count; $i++) {
        $line = $lines[$i]
        if ([string]::IsNullOrWhiteSpace($line)) { continue }

        foreach ($term in $FranchiseTerms) {
            if ($line -imatch $term) {
                $results.Add([pscustomobject]@{
                    File     = $file.FullName.Substring($SourceRoot.Length).TrimStart('\','/')
                    Line     = $i + 1
                    Category = 'franchise-term'
                    Match    = $term
                    Content  = $line.Trim()
                })
            }
        }

        foreach ($pattern in $PortugueseHeuristics) {
            if ($line -imatch $pattern) {
                $results.Add([pscustomobject]@{
                    File     = $file.FullName.Substring($SourceRoot.Length).TrimStart('\','/')
                    Line     = $i + 1
                    Category = 'possible-untranslated'
                    Match    = $pattern
                    Content  = $line.Trim()
                })
                break
            }
        }
    }
}

$outDir = Split-Path -Parent $OutputCsv
if (-not (Test-Path $outDir)) { New-Item -ItemType Directory -Path $outDir | Out-Null }

$results | Sort-Object File, Line | Export-Csv -LiteralPath $OutputCsv -NoTypeInformation -Encoding UTF8

Write-Host "Content audit complete: $($results.Count) flagged lines across $($files.Count) files."
Write-Host "Results written to: $OutputCsv"
