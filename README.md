# AppSalon PHP MVC + JavaScript + SASS

Web application for barbershop appointment management, developed with PHP following the MVC pattern, MySQL, JavaScript, and SASS.  
The system allows users to register, log in, book appointments, and administrators to manage daily schedules.

---

## 🖼️ Preview

| Login                | Dashboard                  |
| -------------------- | -------------------------- |
| <img width="1600" height="675" alt="login" src="https://github.com/user-attachments/assets/750f57d1-1da4-4f50-9f83-868131298fa0" /> | <img width="960" height="1282" alt="DashboardBs" src="https://github.com/user-attachments/assets/12f83119-0566-4d00-adc3-84cced25368b" /> |

---

## ⚙️ Requirements

- PHP 8.1+
- MySQL 8+
- Composer (if external dependencies are used)
- Local server (XAMPP, Laragon, etc.)

---

## Technologies Used

- PHP 8+  
- MySQL  
- Composer  
- MVC (custom architecture)  
- JavaScript  
- SASS  
- Gulp  
- PHPMailer  
- Dotenv  

---

## Main Features

- User registration with email account confirmation.  
- Login and password recovery.  
- Appointment booking for available services.  
- Admin panel to view and manage daily appointments.  
- Service management from the admin module.  
- Responsive design with styles built in SASS.  
- Email sending with PHPMailer.

---

## Project Structure - MVC

- **controllers/**: route controllers and business logic.  
- **models/**: data models and database access logic.  
- **views/**: user interface templates.  
- **includes/**: global configuration, database connection, and environment variables.  
- **public/**: application entry point and public resources.  
- **src/**: source files for styles and JavaScript.  
- **tests/**: basic project tests.  

---

🔐 Example Access
Admin:  
📧 admin@admin.com
🔑 1234567

Client:  
📧 correo2@correo.com
🔑 123456

---

## Installation

1. Clone this repository:

   ```bash
   git clone <repository-url>
   cd AppSalon_PHP_MVC_JS_SASS
   ```

2. Install PHP dependencies:

   ```bash
   composer install
   ```

3. Install frontend dependencies:

   ```bash
   npm install
   ```

4. Configure environment variables in the includes/.env file with your database and email credentials.

5. Create the database in MySQL and adjust the connection according to your environment.

6. Compile frontend asset:

   ```bash
   npm run build
   ```

   For development with hot reload:

   ```bash
   npm run dev
   ```

7. Run the project from your local server and access the public folder.

## Usage
- Users can create an account and confirm access via email.

- Once authenticated, they can schedule available services.

- Administrators can view registered appointments for a specific date and manage system services.

## Environment Variables
The project uses a .env file inside the includes/ folder to define the following:

- Database host
- MySQL username and password
- Database name
- SMTP configuration for emails
- Base URL of the application
