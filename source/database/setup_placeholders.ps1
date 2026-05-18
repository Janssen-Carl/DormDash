$placeholder = "C:\Users\janss\.gemini\antigravity\brain\c3c6449c-1579-44f0-8971-7a1199f29946\placeholder_product_1779056150237.png"
$base = "c:\Users\janss\Documents\GitHub\DormDash\source\public\images"

# Vendor cover & profile images
foreach ($v in 1..5) {
    $dir = Join-Path $base "vendors\$v"
    New-Item -ItemType Directory -Path $dir -Force | Out-Null
    Copy-Item $placeholder (Join-Path $dir "cover.jpg")
    Copy-Item $placeholder (Join-Path $dir "profile.jpg")
}

# Item images
foreach ($i in 1..50) {
    $dir = Join-Path $base "items\$i"
    New-Item -ItemType Directory -Path $dir -Force | Out-Null
    Copy-Item $placeholder (Join-Path $dir "1.jpg")
}

Write-Host "Done: created placeholder images for 5 vendors and 50 items"
