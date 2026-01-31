@echo off
echo ============================================================
echo   Setting up Database for Loyola Lost & Found
echo ============================================================
echo.

cd /d "%~dp0"
echo Current directory: %CD%
echo.

echo Checking MySQL...
if exist "C:\xampp\mysql\bin\mysql.exe" (
    echo MySQL found at C:\xampp\mysql\bin\mysql.exe
    echo.
    echo Importing database schema...
    "C:\xampp\mysql\bin\mysql.exe" -u root < "sql\schema.sql"
    
    if %ERRORLEVEL% EQU 0 (
        echo.
        echo ============================================================
        echo   ✅ Database setup completed successfully!
        echo ============================================================
    ) else (
        echo.
        echo ============================================================
        echo   ❌ Database setup failed!
        echo   Make sure MySQL is running in XAMPP Control Panel
        echo ============================================================
    )
) else (
    echo MySQL not found at C:\xampp\mysql\bin\mysql.exe
    echo Please update the path in this script if XAMPP is installed elsewhere
)

echo.
echo Press any key to exit...
pause >nul


