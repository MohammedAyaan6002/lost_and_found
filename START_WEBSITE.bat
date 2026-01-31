@echo off
echo ============================================================
echo   Starting Loyola Lost & Found Website
echo ============================================================
echo.

echo Checking XAMPP services...
echo.

:: Check if Apache is running
netstat -an | find "0.0.0.0:80" >nul
if %ERRORLEVEL% EQU 0 (
    echo ✅ Apache is running on port 80
) else (
    echo ❌ Apache is NOT running
    echo    Please start Apache from XAMPP Control Panel
    echo.
)

:: Check if MySQL is running
netstat -an | find "0.0.0.0:3306" >nul
if %ERRORLEVEL% EQU 0 (
    echo ✅ MySQL is running on port 3306
) else (
    echo ❌ MySQL is NOT running
    echo    Please start MySQL from XAMPP Control Panel
    echo.
)

echo.
echo ============================================================
echo   Opening your website in browser...
echo ============================================================
echo.

start http://localhost/New%%20Folder/
start http://localhost/New%%20Folder/admin/

echo.
echo URLs opened:
echo   Main Site: http://localhost/New%%20Folder/
echo   Admin: http://localhost/New%%20Folder/admin/
echo.
echo Press any key to exit...
pause >nul


