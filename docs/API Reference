# API Reference

> Generated from the project's directory structure and controller/model naming (`AuthController`, `AppointmentController`, `ServiceController`, `AdminController`, `Router.php`). Exact route paths and payload fields marked `<!-- TODO -->` should be confirmed against the actual code in each controller.

## Overview

BarberShop_App_MVC is a PHP MVC application. Requests are dispatched by `Router.php` / `includes/router.php` to one of four controllers. There is no separate REST/JSON API layer — controllers render views directly and/or handle form POSTs, so this document describes the app's **routes** (page + action endpoints) rather than a JSON API, unless noted otherwise.

- **Base URL:** `<!-- TODO: e.g. http://localhost/BarberShop_App_MVC/public -->`
- **Auth:** Session-based, enforced via `includes/auth.php` middleware.
- **CSRF/validation:** `<!-- TODO: confirm if a CSRF token mechanism is used -->`

## Auth Routes (`AuthController.php`)

| Method | Route | Description | Auth required |
|--------|-------|--------------|----------------|
| GET | `/auth/login` | Show login form | No |
| POST | `/auth/login` | Authenticate user, start session | No |
| POST | `/auth/logout` | Destroy session | Yes |
| GET | `/auth/create-account` | Show registration form | No |
| POST | `/auth/create-account` | Register new user (via `models/User.php`) | No |
| GET | `/auth/confirm-account?token=...` | Confirm account via emailed token | No |
| GET | `/auth/forget-password` | Show "forgot password" form | No |
| POST | `/auth/forget-password` | Send password-reset email (via `classes/Email.php`) | No |
| GET | `/auth/recover-password?token=...` | Show reset-password form | No |
| POST | `/auth/recover-password` | Set new password (`User::updatePassword`) | No |

**Example — login POST body**
```
email=user@example.com&password=********
```

## Appointment Routes (`AppointmentController.php`)

Backed by `models/Appointment.php` and `models/AppointmentService.php` (pivot table linking an appointment to one or more services).

| Method | Route | Description | Auth required |
|--------|-------|--------------|----------------|
| GET | `/appointments` | New booking form / service selection | Yes |
| POST | `/appointments` | Create appointment (`Appointment::create` + pivot rows via `AppointmentService`) | Yes |
| GET | `/appointments/list` | List current user's appointments (`Appointment::findByUser`) | Yes |
| GET | `/appointments/admin?date=YYYY-MM-DD` | Admin daily view (`Appointment::findByDate`) | Yes (admin) |
| POST | `/appointments/cancel/{id}` | Cancel an appointment | Yes |

**Example — create appointment POST body**
```json
{
  "date": "2026-07-15",
  "time": "10:30",
  "services": [1, 3]
}
```
`<!-- TODO: confirm actual field names/format used by AppointmentController -->`

## Service Routes (`ServiceController.php`)

Backed by `models/Service.php`, rendered by `services/create.php`, `services/form.php`, `services/index.php`, `services/update.php`.

| Method | Route | Description | Auth required |
|--------|-------|--------------|----------------|
| GET | `/services` | List all services (`Service::findAll`) | Yes (admin) |
| GET | `/services/create` | New service form | Yes (admin) |
| POST | `/services/create` | Create service (`Service::create`) | Yes (admin) |
| GET | `/services/update/{id}` | Edit service form (`Service::findById`) | Yes (admin) |
| POST | `/services/update/{id}` | Update service (`Service::update`) | Yes (admin) |
| POST | `/services/delete/{id}` | Delete service (`Service::delete`) | Yes (admin) |

## Admin Routes (`AdminController.php`)

| Method | Route | Description | Auth required |
|--------|-------|--------------|----------------|
| GET | `/admin` | Admin dashboard | Yes (admin) |
| GET | `/admin?date=YYYY-MM-DD` | Dashboard filtered by date | Yes (admin) |

`AdminController` also relies on the access guard in `includes/auth.php` to protect all admin-only routes above.

## Email (`classes/Email.php`)

Not a route — an internal service class used by `AuthController` to send:
- Account confirmation email
- Password reset email

Covered by `tests/email_test.php`.

## Errors

This is a server-rendered app rather than a JSON API, so errors are generally surfaced as:
- Redirects with flash messages (rendered via `templates/alerts.php`)
- `<!-- TODO: confirm if any AJAX/JSON endpoints exist (e.g. for searcher.js) and document their response shape -->`

## Front-end / AJAX

`src/js/searcher.js` suggests at least one dynamic/AJAX interaction (likely service search or filtering).

| Method | Route | Description |
|--------|-------|-------------|
| GET/POST | `<!-- TODO: confirm endpoint used by searcher.js -->` | Dynamic search/filter, likely returns JSON |

## Configuration

Database and mail credentials are loaded via `includes/database.php`, expected to come from an untracked `.env` file (per `.gitignore`, added in the "Finalize project setup" commit). Do not commit real credentials.

---
*Generated 2026-06-30 from project structure and commit history. Replace `<!-- TODO -->` markers once verified against source.*

[← Back to main README](../README.md)
