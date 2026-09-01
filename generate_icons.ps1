Add-Type -AssemblyName System.Drawing
$sizes = @(16, 48, 128)

foreach ($s in $sizes) {
    $b = New-Object System.Drawing.Bitmap($s, $s)
    $g = [System.Drawing.Graphics]::FromImage($b)
    $g.SmoothingMode = [System.Drawing.Drawing2D.SmoothingMode]::AntiAlias
    $g.Clear([System.Drawing.Color]::FromArgb(7, 9, 14))
    
    $pen = New-Object System.Drawing.Pen([System.Drawing.Color]::FromArgb(0, 242, 254), [Math]::Max(1, [int]($s / 16)))
    $brush = New-Object System.Drawing.SolidBrush([System.Drawing.Color]::FromArgb(0, 242, 254))
    
    $g.FillEllipse($brush, 2, 2, $s - 4, $s - 4)
    $b.Save("chrome-extension/icon-$s.png", [System.Drawing.Imaging.ImageFormat]::Png)
    $g.Dispose()
    $b.Dispose()
}
Write-Host "PNG Icons Created!"
