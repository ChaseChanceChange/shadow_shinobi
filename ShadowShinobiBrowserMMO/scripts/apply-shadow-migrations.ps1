$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $projectRoot

$migrations = @(
    'database (sql)/migrations/001_shadow_identity.sql',
    'database (sql)/migrations/002_shadow_content_cleanup.sql'
)

foreach ($migration in $migrations) {
    if (-not (Test-Path $migration)) {
        throw "Migration not found: $migration"
    }

    Write-Host "Applying $migration"
    Get-Content -Raw $migration | docker compose exec -T db mariadb -u shadow -pshadow_dev_password shadow_shinobi
    if ($LASTEXITCODE -ne 0) {
        throw "Migration failed: $migration"
    }
}

Write-Host 'Shadow migrations applied successfully.'
