$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $projectRoot

$migrations = @(
    'database (sql)/migrations/001_shadow_identity.sql',
    'database (sql)/migrations/002_shadow_content_cleanup.sql',
    'database (sql)/migrations/003_shadow_final_identity.sql'
)

foreach ($migration in $migrations) {
    if (-not (Test-Path -LiteralPath $migration)) {
        throw "Migration not found: $migration"
    }

    Write-Host "Applying $migration" -ForegroundColor Cyan
    Get-Content -Raw -LiteralPath $migration | docker compose exec -T db mariadb -u shadow -pshadow_dev_password shadow_shinobi
    if ($LASTEXITCODE -ne 0) {
        throw "Migration failed: $migration"
    }
}

Write-Host 'Shadow migrations applied successfully.' -ForegroundColor Green
Write-Host ''
Write-Host 'Current content identity:' -ForegroundColor Cyan
docker compose exec -T db mariadb -u shadow -pshadow_dev_password shadow_shinobi -e "SELECT gamename,class1name,class2name,class3name,diff1name,diff2name,diff3name FROM dk_control WHERE id=1; SELECT id,name FROM dk_towns ORDER BY id; SELECT COUNT(*) AS news_rows FROM dk_news; SELECT COUNT(*) AS babble_rows FROM dk_babble; SELECT COUNT(*) AS map_chat_rows FROM dk_chatmap;"
