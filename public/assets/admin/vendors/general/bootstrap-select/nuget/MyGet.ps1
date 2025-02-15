<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> b7cb4e1d80796a98313415ef7a5d8e797d4f6f04
# set env vars usually set by MyGet (enable for local testing)
#$env:SourcesPath = '..'
#$env:NuGet = "./nuget.exe" #https://dist.nuget.org/win-x86-commandline/latest/nuget.exe

$nuget = $env:NuGet

# parse the version number out of package.json
$bsversionParts = ((Get-Content $env:SourcesPath\package.json) -join "`n" | ConvertFrom-Json).version.split('-', 2) # split the version on the '-'
$bsversion = $bsversionParts[0]

if ($bsversionParts.Length -gt 1)
{
    $bsversion += '-' + $bsversionParts[1].replace('.', '').replace('-', '_')   # strip out invalid chars from the PreRelease part
}

# update sourceMappingURL in bootstrap-select.min.js
(Get-Content $env:SourcesPath\dist\js\bootstrap-select.min.js).replace("sourceMappingURL=", "sourceMappingURL=Scripts/") | Set-Content $env:SourcesPath\dist\js\bootstrap-select.min.js

# create packages
<<<<<<< HEAD
=======
=======
# set env vars usually set by MyGet (enable for local testing)
#$env:SourcesPath = '..'
#$env:NuGet = "./nuget.exe" #https://dist.nuget.org/win-x86-commandline/latest/nuget.exe

$nuget = $env:NuGet

# parse the version number out of package.json
$bsversionParts = ((Get-Content $env:SourcesPath\package.json) -join "`n" | ConvertFrom-Json).version.split('-', 2) # split the version on the '-'
$bsversion = $bsversionParts[0]

if ($bsversionParts.Length -gt 1)
{
    $bsversion += '-' + $bsversionParts[1].replace('.', '').replace('-', '_')   # strip out invalid chars from the PreRelease part
}

# update sourceMappingURL in bootstrap-select.min.js
(Get-Content $env:SourcesPath\dist\js\bootstrap-select.min.js).replace("sourceMappingURL=", "sourceMappingURL=Scripts/") | Set-Content $env:SourcesPath\dist\js\bootstrap-select.min.js

# create packages
>>>>>>> origin/Abdelrahman_shikhan-10
>>>>>>> b7cb4e1d80796a98313415ef7a5d8e797d4f6f04
& $nuget pack "$env:SourcesPath\nuget\bootstrap-select.nuspec" -Verbosity detailed -NonInteractive -NoPackageAnalysis -BasePath $env:SourcesPath -Version $bsversion