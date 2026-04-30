Get-ChildItem -Path "resources/views/admin", "resources/views/host" -Filter *.blade.php -Recurse | ForEach-Object {
    $content = Get-Content $_.FullName -Raw
    $newContent = $content -replace 'text-uppercase\s*', ''
    $newContent = $newContent -replace '\s*text-uppercase', ''
    if ($content -ne $newContent) {
        Set-Content -Path $_.FullName -Value $newContent
    }
}
