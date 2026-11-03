<#
.SYNOPSIS
    Scrapes the official VIAR product pages for product name, banner image, and
    specification table. Output is consumed by import-official-catalog.php.

.EXAMPLE
    powershell -ExecutionPolicy Bypass -File scripts\scrape-official-catalog.ps1
#>
[CmdletBinding()]
param(
    [string]$OutputPath = ''
)

$ErrorActionPreference = 'Stop'

if (-not $OutputPath) {
    $scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
    $OutputPath = Join-Path $scriptDir 'official-catalog.json'
}

$productUrls = @(
    'https://viarmotor.com/commercials-karya-bit/',
    'https://viarmotor.com/commercials-new-karya-150l/',
    'https://viarmotor.com/commercials-new-karya-200n-l/',
    'https://viarmotor.com/commercials-new-karya-300/',
    'https://viarmotor.com/commercials-karya-bit-ram/',
    'https://viarmotor.com/commercials-karya-150rn-rln-ram/',
    'https://viarmotor.com/commercials-karya-200ln-ram/',
    'https://viarmotor.com/commercials-karya-300nn-ram/',
    'https://viarmotor.com/commercials-karya-300-mdt-bak-sampah-terbuka/',
    'https://viarmotor.com/commercials-karya-150-box-alumunium-profile/',
    'https://viarmotor.com/commercials-karya-200-r-ppk/',
    'https://viarmotor.com/commercials-karya-200l-mdt-bak-terbuka/',
    'https://viarmotor.com/commercials-karya-200l-hmdt-bak-tertutup/',
    'https://viarmotor.com/commercials-karya-mbg-200-ln/',
    'https://viarmotor.com/commercials-karya-e5/',
    'https://viarmotor.com/commercials-vxt-350-s/',
    'https://viarmotor.com/commercials-vxt-300/',
    'https://viarmotor.com/commercials-vxt-350/',
    'https://viarmotor.com/commercials-new-karya-karya-traktor-200-300/',
    'https://viarmotor.com/commercials-karya-r5k/',
    'https://viarmotor.com/commercials-karya-r6k/',
    'https://viarmotor.com/commercials-agri-r2k/',
    'https://viarmotor.com/off-road-mini-trail-100/',
    'https://viarmotor.com/off-road-mini-trail-70/',
    'https://viarmotor.com/off-road-cross-xf-150/',
    'https://viarmotor.com/off-road-crossx-150-xf/',
    'https://viarmotor.com/off-road-razor-110-ut/',
    'https://viarmotor.com/off-road-razor-150ut/',
    'https://viarmotor.com/off-road-razor-200rzm/',
    'https://viarmotor.com/subsidi-motor-listrik-viar-new-q1/',
    'https://viarmotor.com/motor-listrik-viar-ev1/',
    'https://viarmotor.com/motor-listrik-viar-nx/',
    'https://viarmotor.com/sepeda-listrik-roda-tiga-viar-er2/',
    'https://viarmotor.com/sepeda-listrik-roda-tiga-viar-er1-n/',
    'https://viarmotor.com/sepeda-listrik-viar-vitron-o2/'
)

function ConvertTo-PlainText {
    param([string]$Html)
    if (-not $Html) { return '' }
    $text = $Html -replace '<br\s*/?>', ' '
    $text = $text -replace '<[^>]+>', ' '
    $text = [System.Net.WebUtility]::HtmlDecode($text)
    $text = $text -replace '\s+', ' '
    return $text.Trim()
}

function Get-ProductName {
    param([string]$Html, [string]$Url)
    $match = [regex]::Match($Html, '<h1[^>]*>([\s\S]*?)</h1>', 'IgnoreCase')
    if ($match.Success) {
        $name = ConvertTo-PlainText $match.Groups[1].Value
        if ($name) { return $name }
    }
    $match = [regex]::Match($Html, '<title[^>]*>([\s\S]*?)</title>', 'IgnoreCase')
    if ($match.Success) {
        $name = (ConvertTo-PlainText $match.Groups[1].Value) -replace '\s*[-–|]\s*VIAR.*$', ''
        if ($name) { return $name }
    }
    return ($Url -split '/')[-2]
}

function Get-Specifications {
    param([string]$Html)
    $specs = [ordered]@{}
    $tables = [regex]::Matches($Html, '<table[\s\S]*?</table>', 'IgnoreCase')
    foreach ($table in $tables) {
        $rows = [regex]::Matches($table.Value, '<tr[^>]*>([\s\S]*?)</tr>', 'IgnoreCase')
        foreach ($row in $rows) {
            $cells = [regex]::Matches($row.Groups[1].Value, '<t[dh][^>]*>([\s\S]*?)</t[dh]>', 'IgnoreCase')
            if ($cells.Count -lt 2) { continue }
            $label = ConvertTo-PlainText $cells[0].Groups[1].Value
            $value = ConvertTo-PlainText $cells[1].Groups[1].Value
            if (-not $label -or -not $value) { continue }
            if ($label.Length -gt 60 -or $value.Length -gt 120) { continue }
            if ($specs.Contains($label)) { continue }
            $specs[$label] = $value
        }
    }
    return $specs
}

function Get-BannerImage {
    param([string]$Html)
    $matches = [regex]::Matches($Html, 'https://viarmotor\.com/wp-content/uploads/[^"''\)\s]+\.(?:jpg|jpeg|png|webp)', 'IgnoreCase')
    $candidates = @()
    foreach ($match in $matches) {
        $url = $match.Value
        if ($url -match '(?i)logo|cropped|pavicon|icon|Spesifikasi|favicon') { continue }
        $candidates += $url
    }
    if (-not $candidates.Count) { return '' }
    return $candidates[0]
}

$catalog = @()
$index = 0
foreach ($url in $productUrls) {
    $index++
    Write-Host "[$index/$($productUrls.Count)] $url"
    try {
        $response = Invoke-WebRequest -Uri $url -UseBasicParsing -TimeoutSec 60 -Headers @{ 'User-Agent' = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)' }
        $html = $response.Content
        $catalog += [pscustomobject]@{
            url       = $url
            name      = Get-ProductName -Html $html -Url $url
            image     = Get-BannerImage -Html $html
            specs     = Get-Specifications -Html $html
        }
    } catch {
        Write-Warning "Gagal mengambil $url : $($_.Exception.Message)"
    }
}

$catalog | ConvertTo-Json -Depth 6 | Set-Content -Path $OutputPath -Encoding UTF8
Write-Host "Selesai: $($catalog.Count) produk disimpan ke $OutputPath"
