# Quick Start Guide - Run Website on Localhost

## Prerequisites Check
1. **XAMPP** should be installed at `C:\xampp\`
2. Your project is located at `C:\xampp\htdocs\New Folder\`

## Step 1: Start XAMPP Services

1. Open **XAMPP Control Panel**
2. Start these services:
   - ✅ **Apache** (Click "Start" button)
   - ✅ **MySQL** (Click "Start" button)

Wait until both services show "Running" status (green).

## Step 2: Setup Database

1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click on **"New"** in the left sidebar to create a database
3. Or run this SQL:
   - Go to **"Import"** tab
   - Click **"Choose File"** and select: `sql/schema.sql`
   - Click **"Go"** button

Alternatively, you can run this in Command Prompt:
```bash
cd C:\xampp\mysql\bin
mysql.exe -u root < "C:\xampp\htdocs\New Folder\sql\schema.sql"
```

## Step 3: Access Your Website

### Main Website:
**URL:** `http://localhost/New%20Folder/`

### Admin Dashboard:
**URL:** `http://localhost/New%20Folder/admin/`
or
**URL:** `http://localhost/New%20Folder/admin/dashboard.php`

## Step 4: (Optional) Start Flask AI Service

The AI matching features require the Flask service to be running:

1. Open Command Prompt
2. Navigate to the flask-service folder:
   ```bash
   cd "C:\xampp\htdocs\New Folder\flask-service"
   ```
3. Create virtual environment (first time only):
   ```bash
   python -m venv venv
   venv\Scripts\activate
   ```
4. Install dependencies (first time only):
   ```bash
   pip install -r requirements.txt
   python -m spacy download en_core_web_sm
   ```
5. Start the Flask service:
   ```bash
   python app.py
   ```

The Flask service will run on `http://127.0.0.1:5001`

## Troubleshooting

### If you see "Failed to connect to MySQL":
- Make sure MySQL service is running in XAMPP Control Panel
- Check database name is `loyola_lost_and_found`
- Verify credentials in `includes/db.php` (default: root, no password)

### If you see 404 errors:
- Check Apache is running in XAMPP
- Verify folder name is exactly "New Folder" (with space)
- Try: `http://localhost/New%20Folder/index.php`

### If images/uploads don't work:
- Check that `uploads/` folder exists and is writable

## Quick Test

1. Visit: `http://localhost/New%20Folder/`
2. You should see the homepage
3. Visit: `http://localhost/New%20Folder/admin/`
4. You should see the admin dashboard

---

**Note:** The folder name has a space, so use `%20` in URLs or `New%20Folder`


