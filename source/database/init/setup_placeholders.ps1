# Ensure placeholder directories exist and are clean
$publicDir = Join-Path $PSScriptRoot "..\..\public"
$itemsDir = Join-Path $publicDir "images\items"
$vendorsDir = Join-Path $publicDir "images\vendors"

# Ensure target directories exist
New-Item -ItemType Directory -Force -Path (Join-Path $itemsDir "1") | Out-Null
New-Item -ItemType Directory -Force -Path (Join-Path $vendorsDir "1") | Out-Null

# Copy default image if it exists in parent path
$rootImage = Join-Path $PSScriptRoot "..\..\..\1.jpg"
if (Test-Path $rootImage) {
    Copy-Item -Path $rootImage -Destination (Join-Path $itemsDir "1\1.jpg") -Force
    Copy-Item -Path $rootImage -Destination (Join-Path $vendorsDir "1\1.jpg") -Force
}
