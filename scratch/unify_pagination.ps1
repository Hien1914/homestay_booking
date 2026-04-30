Get-ChildItem -Path "app/Http/Controllers" -Filter *.php -Recurse | ForEach-Object {
    $content = Get-Content $_.FullName -Raw
    $newContent = $content -replace 'paginate\(\d+', 'paginate(15'
    if ($content -ne $newContent) {
        Set-Content -Path $_.FullName -Value $newContent
    }
}
