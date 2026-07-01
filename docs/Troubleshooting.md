# 🔧 Troubleshooting Guide — Barbershop PHP MVC

> Step-by-step fixes for the most common issues in AppSalon.

---

## Table of Contents

- [Quick Reference](#quick-reference)
- [1. Installation & Setup](#1-installation--setup)
- [2. Database](#2-database)
- [3. Authentication](#3-authentication)
- [4. Email / PHPMailer](#4-email--phpmailer)
- [5. Routing & Pages](#5-routing--pages)
- [6. Frontend — SASS & JavaScript](#6-frontend--sass--javascript)
- [7. PHP Errors & Logging](#7-php-errors--logging)
- [8. Environment Variables (.env)](#8-environment-variables-env)
- [9. Appointments](#9-appointments)
- [10. General Debugging Tips](#10-general-debugging-tips)

---

## Quick Reference

| Error / Symptom | Area | First thing to check |
|-----------------|------|----------------------|
| `SQLSTATE[HY000] [2002] Connection refused` | Database | DB credentials in `includes/.env` |
| `Class not found / autoload error` | Composer | Run: `composer install` |
| Blank page / no output | PHP | Enable error reporting or check PHP error log |
| Email not received after registration | PHPMailer / SMTP | SMTP credentials in `.env` + Mailtrap inbox |
| Token invalid — account not confirmed | Auth / Email | Token expired or URL truncated |
| Login fails with correct password | Auth / Session | `session_start()` missing or bcrypt mismatch |
| Admin panel shows 403 / redirects away | Auth middleware | `admin` flag = 0 in users table |
| CSS/JS not loading (404 on `/build/`) | Gulp / Assets | Run: `npm install && npm run build` |
| SASS changes not reflected in browser | Gulp | Run: `npm run dev` (watch mode) |
| Appointment double-booked same slot | Validation | Server-side time-conflict check missing |
| 500 Internal Server Error | PHP / PDO | Check PHP error log; enable `display_errors` temporarily |
| `.env` variables return null | Dotenv | File path wrong or dotenv not loaded first |

---

## 1. Installation & Setup

### Composer: command not found

**Symptom:** Running `composer install` throws `command not found`.

- Install Composer globally: https://getcomposer.org/download/
- Or run via PHP directly: `php composer.phar install`

---

### composer install fails — package version conflicts

**Symptom:** Composer exits with dependency resolution errors.

- Check your PHP version: `php --version` (must be 8.1+)
- Try: `composer install --ignore-platform-reqs` (diagnosis only)
- Delete `vendor/` and `composer.lock`, then re-run `composer install`

---

### npm install / npm run build fails

**Symptom:** Frontend assets don't compile; terminal shows errors.

- Verify Node.js version: `node --version` (v16+ recommended)
- Delete `node_modules/` and `package-lock.json`, then re-run `npm install`
- If Gulp errors appear: `npm install -g gulp-cli`, then `npm run build`

---

### PHP built-in server: 'Address already in use'

**Symptom:** `php -S localhost:8000` fails with address in use error.

```bash
# macOS / Linux — kill whatever is using port 8000
lsof -ti:8000 | xargs kill -9

# Windows
netstat -ano | findstr :8000
taskkill /PID <PID> /F
```

Or change the port:
```bash
php -S localhost:8080 -t public
```

---

## 2. Database

### SQLSTATE[HY000] [2002] Connection refused

**Symptom:** Every page shows a PDO connection error.  
**Cause:** DB credentials in `includes/.env` are wrong, or MySQL is not running.

1. Verify MySQL is running:
```bash
# XAMPP: check the control panel (MySQL must show 'Running')
# macOS:
brew services list | grep mysql
# Linux:
sudo systemctl status mysql
```

2. Check `includes/.env`:
```env
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=appsalon
```

3. Test the connection manually:
```bash
mysql -u root -p -h localhost appsalon
```

---

### Table doesn't exist

**Symptom:** `SQLSTATE[42S02]: Base table or view not found.`  
**Cause:** The database schema was not created.

- Run the `CREATE TABLE` script from the [README](../README.md#database)
- Verify the database name in `.env` matches the one you created
- Check that the MySQL user has SELECT/INSERT/UPDATE/DELETE privileges

---

### Duplicate entry for key 'email'

**Symptom:** Registration fails with a MySQL duplicate key error.

- The user already registered — redirect them to login or password recovery
- In development, clear the test record:
```sql
DELETE FROM users WHERE email = 'test@test.com';
```
- Confirm the controller catches this `PDOException` and shows a friendly message

---

### Foreign key constraint fails on appointment delete

**Symptom:** Deleting an appointment throws an integrity constraint violation.  
**Cause:** Rows in `apptservices` still reference the appointment.

Delete in the correct order:
```sql
DELETE FROM apptservices WHERE appointID = :id;
DELETE FROM appointments WHERE id = :id;
```

Or add `ON DELETE CASCADE` to the foreign key in `apptservices`.

---

## 3. Authentication

### Login fails — correct password rejected

**Symptom:** `password_verify()` returns false with the right password.  
**Cause:** Password in the DB is not a valid bcrypt hash.

1. Check the stored value:
```sql
SELECT password FROM users WHERE email = 'user@example.com';
```
A valid bcrypt hash starts with `$2y$`. If it doesn't, the password was stored raw.

2. Re-hash it:
```bash
php -r "echo password_hash('yourpassword', PASSWORD_BCRYPT);"
```

3. Update the record:
```sql
UPDATE users SET password = '<hashed>' WHERE email = 'user@example.com';
```

---

### Account confirmation link not working

**Symptom:** Clicking the confirmation link shows "token invalid" or 404.

- Token may have expired or been used already — check the `token` column in `users`
- `APP_URL` in `.env` may not match the actual server URL:
```env
# Correct
APP_URL=http://localhost:8000

# Wrong (trailing slash can break routing)
APP_URL=http://localhost:8000/
```
- Email client may have wrapped the link — copy-paste the full URL into the browser
- To manually confirm a user during development:
```sql
UPDATE users SET confirmed = 1, token = NULL WHERE email = 'user@example.com';
```

---

### Admin panel redirects to home / shows 403

**Symptom:** Logged-in user cannot access `/admin`.  
**Cause:** The user's `admin` column is `0` (client role).

```sql
-- Check the flag
SELECT email, admin, confirmed FROM users WHERE email = 'admin@admin.com';

-- Promote to admin
UPDATE users SET admin = 1 WHERE email = 'admin@admin.com';
```

Also confirm that `auth.php` middleware checks `$_SESSION['admin']` and the session is correctly populated on login.

---

### Session lost between pages

**Symptom:** User is logged out randomly or after navigating.

- Confirm `session_start()` is called at the very top of every page — before any output
- Any `echo` or whitespace before `session_start()` causes headers-already-sent warnings that break sessions
- Check `php.ini` session settings: `session.gc_maxlifetime` and `session.cookie_lifetime`

---

### Password recovery email never arrives

**Symptom:** User requests recovery but receives no email.

```sql
-- Check if the token was generated
SELECT token FROM users WHERE email = 'user@example.com';
```

- If `token` is `NULL` — the controller failed before saving. Check PHP error logs.
- If token exists — the email dispatch failed. See [Section 4](#4-email--phpmailer).

---

## 4. Email / PHPMailer

### Emails not appearing in Mailtrap inbox

**Symptom:** Registration or password recovery completes but no email arrives.

1. Verify Mailtrap credentials in `includes/.env`:
```env
SMTP_HOST=smtp.mailtrap.io
SMTP_PORT=2525
SMTP_USER=<your_mailtrap_user>
SMTP_PASS=<your_mailtrap_password>
```

2. Re-copy credentials from the Mailtrap dashboard → Inboxes → SMTP Settings.

3. Confirm PHPMailer is installed:
```bash
ls vendor/phpmailer/phpmailer
```

4. Add temporary debug output:
```php
$mail->SMTPDebug = 2; // prints SMTP negotiation to screen
```

5. Try port `587` if `2525` is blocked.

---

### PHPMailer: SMTP connect() failed

**Symptom:** Exception: `SMTP connect() failed`.

- Port is blocked. Try: `2525`, `587`, or `465`
- Firewall or antivirus blocking outbound SMTP — disable temporarily to test

```php
// Port 465 (SSL)
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
$mail->Port = 465;

// Port 587 (STARTTLS)
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
```

---

### PHPMailer: Class not found

**Symptom:** `Fatal error: Class 'PHPMailer\PHPMailer\PHPMailer' not found.`

```php
// Add at the top of the file that uses PHPMailer
require_once __DIR__ . '/../vendor/autoload.php';
```

Or run `composer install` again if `vendor/` is missing.

---

## 5. Routing & Pages

### All routes return 404

**Symptom:** Every URL except the root shows a 404.  
**Cause:** URL rewriting is not configured.

**Apache `.htaccess`** (place in `public/`):
```apache
Options -Indexes
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

**PHP built-in server:**
```bash
php -S localhost:8000 -t public public/index.php
```

---

### Route works but shows blank page

**Symptom:** The URL resolves but the browser shows nothing.

1. Enable error reporting temporarily:
```php
// At the top of public/index.php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```
2. Check the PHP error log for the real error (see [Section 7](#7-php-errors--logging))
3. Verify the view file exists and the path in the controller is correct

---

### Controller method not called

**Symptom:** Navigating to `/appointments/new` 404s or does nothing.

- Check `includes/router.php` — confirm it maps the URI to the correct class name
- PHP class files are **case-sensitive on Linux**: `AppointmentController.php` must match exactly
- Verify the method (e.g. `new` or `create`) is `public` and matches the router mapping

---

## 6. Frontend — SASS & JavaScript

### CSS not loading (404 on /build/app.css)

**Symptom:** Browser console shows 404 for `public/build/app.css`.

```bash
npm install
npm run build
```

Verify the `<link>` tag in your view:
```html
<link rel="stylesheet" href="/build/app.css">
```

---

### SASS changes not visible in browser

**Symptom:** Editing a `.scss` file has no effect.

```bash
npm run dev   # starts Gulp in watch mode
```

- Hard-refresh the browser: `Ctrl+Shift+R` / `Cmd+Shift+R`
- Check the Gulp terminal for SASS syntax errors — a parse error stops the watch silently

---

### JavaScript not executing

**Symptom:** Frontend interactions don't respond.

- Open DevTools (F12) → **Console** tab — look for JS errors
- Confirm `public/build/app.js` was compiled: `npm run build`
- Verify the `<script>` tag is at the **bottom of `<body>`**, after the DOM elements it references

---

## 7. PHP Errors & Logging

### Enable error output (development only)

> ⚠️ Never enable `display_errors` in production.

```php
// At the top of public/index.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
```

---

### Where to find the PHP error log

| Environment | Log path |
|-------------|----------|
| XAMPP (Windows) | `C:\xampp\php\logs\php_error_log` |
| XAMPP (macOS) | `/Applications/XAMPP/logs/php_error_log` |
| Laragon | `C:\laragon\bin\php\php-8.x\logs\php_error_log` |
| Linux (Apache) | `/var/log/apache2/error.log` |
| PHP built-in server | Errors print directly to the terminal |

Find the active log path:
```bash
php -r "echo ini_get('error_log');"
```

---

### 500 Internal Server Error

**Symptom:** Browser shows a generic 500 with no details.

1. Enable `display_errors` as shown above
2. Check the PHP error log for the actual exception
3. Common causes:
   - Syntax error in a PHP file (parse error)
   - Missing `require`/`include` — path is wrong
   - PDO throws an uncaught exception
   - PHPMailer throws on SMTP failure

---

### Class not found / autoload not working

**Symptom:** `Fatal error: Class 'App\Controllers\SomeController' not found.`

```bash
composer dump-autoload
```

- Check the namespace in the PHP file matches the directory structure and `composer.json`
- Verify `require_once __DIR__ . '/../vendor/autoload.php';` is in `index.php`

---

## 8. Environment Variables (.env)

### .env values return null or empty

**Symptom:** `$_ENV['DB_HOST']` or `getenv('DB_HOST')` returns null.

1. Confirm the `.env` file is at `includes/.env`
2. Confirm the dotenv loader is the **very first thing** that runs:
```php
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
```
3. Check for syntax errors in `.env` — **no spaces around `=`**:
```env
DB_HOST=localhost    # correct
DB_HOST = localhost  # wrong
```
4. Variable names are case-sensitive — `DB_HOST` ≠ `db_host`

---

### .env committed to git by mistake

**Symptom:** `includes/.env` appears in `git status` or `git log`.

```bash
# 1. Add to .gitignore
echo "includes/.env" >> .gitignore

# 2. Remove from git tracking (does NOT delete the file)
git rm --cached includes/.env
git commit -m "Remove .env from tracking"
```

> ⚠️ If credentials were already pushed, **rotate all secrets immediately** (DB password, SMTP credentials).

Create a safe placeholder instead:
```bash
cp includes/.env includes/.env.example
# Replace real values with placeholders in .env.example, then commit it
```

---

## 9. Appointments

### Double booking — same time slot booked twice

**Symptom:** Two clients can book the same date and time.  
**Cause:** Server-side conflict check is missing (known gap).

Add a conflict query in the Appointment model before inserting:
```php
$stmt = $pdo->prepare(
    "SELECT id FROM appointments WHERE date = :date AND time = :time LIMIT 1"
);
$stmt->execute([':date' => $date, ':time' => $time]);

if ($stmt->fetch()) {
    // Slot is taken — return error
}
```

Also mark booked slots as disabled in the frontend (fetch available slots via AJAX).

---

### Appointment saved but services not linked

**Symptom:** Appointment appears in the DB but `apptservices` is empty.

- The service IDs were not submitted with the form — check field names match what the controller expects
- The pivot insert uses `lastInsertId()` — confirm it runs **after** the appointment INSERT:
```php
$appointmentId = $pdo->lastInsertId();
foreach ($serviceIds as $serviceId) {
    // INSERT into apptservices
}
```

---

### Admin: appointments not showing for selected date

**Symptom:** Admin selects a date but no results appear, even though records exist.

- MySQL expects date format `YYYY-MM-DD`. Convert if the date picker sends `DD/MM/YYYY`
- Log the exact value being sent:
```php
error_log('Date received: ' . $date);
```
- Run the query directly in MySQL to confirm records exist for that date

---

## 10. General Debugging Tips

- Use `error_log('value: ' . print_r($var, true));` to log variables without breaking the page
- Use **Postman** or **curl** to test controller endpoints directly, bypassing the view
- Check Apache/Nginx access logs alongside PHP error logs for the full picture
- After any `.env` change, **restart the PHP built-in server** — it does not hot-reload env vars
- Use `git diff` to confirm local file changes are saved before debugging
- If Gulp stops watching without error, kill it (`Ctrl+C`) and restart `npm run dev`
- A blank page almost always means a PHP fatal error with `display_errors = Off`

---

## Still stuck?

Open a GitHub issue with:

- The exact error message or HTTP status code
- PHP version (`php --version`) and OS
- The relevant controller, model, or view snippet
- Contents of the PHP error log around the time of the error
- Whether the issue occurs on a fresh `composer install` + `npm run build`

[← Back to main README](../README.md)

---

← [Back to README](../README.md) | [Architecture](ARCHITECTURE.md)
