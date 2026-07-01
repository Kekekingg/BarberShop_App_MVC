# Testing Guide

> Generated from the project's directory structure and commit history (the "Finalize project setup: add .gitignore, .env config, and test files" commit added `tests/`). Commands marked `<!-- TODO -->` should be confirmed against `composer.json`.

## Overview

The project currently includes one PHP test file:

- `tests/email_test.php` — exercises `classes/Email.php` (account confirmation / password-reset emails).

## Prerequisites

- PHP `<!-- TODO: confirm version, check composer.json -->`
- Composer dependencies installed:
  ```bash
  composer install
  ```
- npm dependencies installed (for the SCSS/JS build):
  ```bash
  npm install
  ```
- A configured `.env` file (see `.gitignore` — it's untracked) with valid database and mail credentials for a **dedicated test database**, not production.

## Running Tests

If the project uses PHPUnit (check `composer.json` under `require-dev`):

```bash
./vendor/bin/phpunit tests/
```

To run just the email test:

```bash
./vendor/bin/phpunit tests/email_test.php
```

If tests are currently plain PHP scripts rather than a PHPUnit suite, they can be run directly:

```bash
php tests/email_test.php
```

`<!-- TODO: confirm whether tests use PHPUnit, Pest, or plain scripts -->`

## Test Coverage Areas

### 1. Email (`classes/Email.php`)
- Confirmation email is sent on registration
- Password-reset email is sent and contains a valid token
- Failures are handled/logged rather than crashing the request
- **Currently covered by:** `tests/email_test.php`

### 2. Auth (`controllers/AuthController.php`, `models/User.php`, `includes/auth.php`)
- Registration validates required fields and rejects duplicate emails
- Login succeeds with valid credentials, fails with invalid ones
- Account confirmation token is validated and single-use
- Password recovery: request → token → reset flow
- Session middleware in `includes/auth.php` correctly blocks unauthenticated/unauthorized access
- `<!-- TODO: no test file currently present for this area -->`

### 3. Appointments (`controllers/AppointmentController.php`, `models/Appointment.php`, `models/AppointmentService.php`)
- Creating an appointment persists correct date/time and linked services (pivot rows)
- Conflicting/overlapping time slots are rejected
- `findByUser` and `findByDate` return correct, scoped results
- Cancelling an appointment removes/updates it and its pivot rows
- `<!-- TODO: no test file currently present for this area -->`

### 4. Services (`controllers/ServiceController.php`, `models/Service.php`)
- CRUD operations (`findAll`, `findById`, `create`, `update`, `delete`) behave as expected
- Deleting a service in use by existing appointments is handled gracefully (via `AppointmentService`)
- `<!-- TODO: no test file currently present for this area -->`

### 5. Admin (`controllers/AdminController.php`)
- Dashboard correctly filters appointments by date
- Access guard blocks non-admin users
- `<!-- TODO: no test file currently present for this area -->`

## Writing New Tests

- Place new PHP test files under `tests/`, following the `<subject>_test.php` naming used by `email_test.php`.
- Each test should set up and clean up its own data so tests can run in any order.
- Always run against the dedicated test database configured via `.env` — never against production data.
- Mock/stub outgoing email sends so tests don't send real emails.

## Front-End Build Verification

`gulpfile.js` + `package.json` drive the SCSS → CSS and JS build (`src/scss/`, `src/js/` → `public/build/`). Rebuild assets before manual QA:

```bash
npx gulp
```
`<!-- TODO: confirm actual gulp task name(s) in gulpfile.js, e.g. `gulp build` -->`

## Manual QA Checklist

Until automated coverage expands beyond the email test, manually verify:

- [ ] Create account → confirm account via email link
- [ ] Login / logout
- [ ] Forgot password → reset password
- [ ] Book, view, and cancel an appointment (with one or more services attached)
- [ ] Create, view, update, delete a service (as admin)
- [ ] Admin dashboard loads and filters correctly by date
- [ ] Unauthenticated/non-admin users are correctly blocked from protected routes

---
*Generated 2026-06-30 from project structure and commit history. Update as the test suite grows.*

[← Back to main README](../README.md)
