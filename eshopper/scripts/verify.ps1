$ErrorActionPreference = 'Stop'
function Invoke-Checked {
    param([string[]]$CommandArgs)
    & php @CommandArgs
    if ($LASTEXITCODE -ne 0) { throw "PHP command failed: $CommandArgs" }
}
Invoke-Checked -CommandArgs @('artisan','route:list')
Invoke-Checked -CommandArgs @('artisan','view:cache')
Invoke-Checked -CommandArgs @('artisan','test','--filter=FoundationTest')
Invoke-Checked -CommandArgs @('artisan','view:clear')
Write-Host 'Foundation checks completed. MySQL concurrency T14 still requires a separate test.'
