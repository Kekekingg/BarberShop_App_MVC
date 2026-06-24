# 💈 Barbershop Management MVC App

Web application developed with **PHP & MySQL** using the **MVC pattern**, designed for managing appointments, clients, and services in a barbershop.  
Includes secure authentication, full CRUD operations, and an admin panel.

---

## 🖼️ Preview

| Login                | Dashboard                  |
| -------------------- | -------------------------- |
| ![login](img/login.png) | ![dashboard](img/dashboard.png) |

---

## ⚙️ Requirements

- PHP 8.1+
- MySQL 8+
- Composer (if external dependencies are used)
- Local server (XAMPP, Laragon, etc.)

---

## 🚀 Installation

```bash
# Clone the repository
git clone https://github.com/Kekekingg/BarberShop_App_MVC.git
cd BarberShop_App_MVC

# Configure database
import barber.sql into MySQL

# Adjust credentials in config/db.php

# Start local server
php -S localhost:8000 -t public
