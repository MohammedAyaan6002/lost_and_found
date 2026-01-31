@echo off
echo ============================================================
echo   Starting Flask AI Matching Service
echo ============================================================
echo.

cd /d "%~dp0flask-service"
echo Current directory: %CD%
echo.

:: Check if Python is installed
python --version >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo ❌ Python is not installed or not in PATH
    echo    Please install Python 3.10+ from https://www.python.org/
    echo.
    pause
    exit /b 1
)

echo ✅ Python found
python --version
echo.

:: Check if virtual environment exists
if not exist "venv" (
    echo Creating virtual environment...
    python -m venv venv
    if %ERRORLEVEL% NEQ 0 (
        echo ❌ Failed to create virtual environment
        pause
        exit /b 1
    )
    echo ✅ Virtual environment created
    echo.
)

:: Activate virtual environment
echo Activating virtual environment...
call venv\Scripts\activate.bat

:: Install/upgrade dependencies
echo.
echo Installing dependencies (this may take a few minutes on first run)...
pip install -r requirements.txt --quiet
if %ERRORLEVEL% NEQ 0 (
    echo ❌ Failed to install dependencies
    pause
    exit /b 1
)

:: Download spaCy model if not present
echo.
echo Checking spaCy model...
python -c "import spacy; spacy.load('en_core_web_sm')" >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo Downloading spaCy English model (this may take a few minutes)...
    python -m spacy download en_core_web_sm
    if %ERRORLEVEL% NEQ 0 (
        echo ⚠️  Warning: Could not download spaCy model. AI matching may not work optimally.
    )
) else (
    echo ✅ spaCy model already installed
)

echo.
echo ============================================================
echo   Starting Flask AI Service on port 5001...
echo ============================================================
echo.
echo The service will be available at: http://127.0.0.1:5001/match
echo.
echo Keep this window open while using AI matching features.
echo Press Ctrl+C to stop the service.
echo.
echo ============================================================
echo.

python app.py


