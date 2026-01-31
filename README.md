# Loyola Lost & Found Platform

Centralized lost-and-found web app for Loyola College students and staff. Built with PHP (XAMPP), MySQL, Bootstrap, and a Python Flask AI microservice for text similarity.

## Requirements

- XAMPP (Apache + PHP + MySQL)
- Python 3.10+
- pip packages: `flask`, `scikit-learn`, `spacy`, `numpy`

## Setup

1. **Clone / Copy**
   Place this folder inside `xampp/htdocs`.

2. **MySQL**
   - Import `sql/schema.sql` via phpMyAdmin or `mysql -u root < sql/schema.sql`.
   - Update DB credentials in `includes/db.php` if needed.
   - Adjust `APP_BASE_URL` in `includes/config.php` if your folder name or host differs.

3. **File Permissions**
   - Ensure `/uploads` is writable by Apache.

4. **Flask AI Service**
   ```bash
   cd flask-service
   python -m venv venv
   venv\Scripts\activate
   pip install -r requirements.txt
   python -m spacy download en_core_web_sm
   python app.py
   ```
   - App listens on `http://127.0.0.1:5001/match` (configurable via `includes/config.php`).

5. **Run**
   - Start Apache + MySQL in XAMPP Control Panel.
   - Visit `http://localhost/New%20folder/index.php`.

## Features

- Lost & Found submission forms with validation and image upload.
- Admin dashboard for approvals, match logs, notifications.
- Search page with AI suggestions via Flask API.
- Notification log for match alerts.

## API Notes

- `POST /api/submit-item.php` – multipart form data for lost/found reports.
- `POST /api/moderate-item.php` – JSON `{id, action}` for admin.
- `POST /api/ai-match.php` – JSON `{description}`; internally forwards to Flask service and stores logs/notifications when score ≥ 0.6.

## Testing

- Use sample data from schema.
- Submit new reports, approve via admin dashboard, run AI suggestions from search page.

## Next Steps

- Hook up authentication for admin routes.
- Configure email/SMS provider inside `api/ai-match.php`.

