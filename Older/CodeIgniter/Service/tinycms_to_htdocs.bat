echo off
echo Kopiere Homepage "TinyCMS" nach "htdocs\tinycms"
echo _
echo _

xcopy F:\Projects\WebModules\TinyCMS\Application\*.* C:\xampp\htdocs\tinycms /d /e /c /i /f /g /r /y
copy F:\Projects\WebModules\TinyCMS\Application\application\config\config_localhost.php C:\xampp\htdocs\tinycms\application\config\config.php /y

echo _
echo _
echo Kopiervorgang erfolgreich beendet
echo _
echo _
timeout /T 3
