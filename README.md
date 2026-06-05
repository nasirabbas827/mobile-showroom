# Mobile_Showroom_final

A PHP‑based web application that showcases mobile phones, allows users to browse, compare, and review devices, and provides an admin panel for managing inventory, categories, and user feedback.

---

## Overview

Mobile_Showroom_final is a lightweight, server‑side solution for a mobile phone showroom. It offers:

* A public catalog of mobile devices with detailed specifications.
* User registration, authentication, and password‑reset flows.
* Ability for visitors to add reviews and compare multiple phones side‑by‑side.
* An admin dashboard for managing categories, mobiles, and moderating reviews.

The project is built with plain PHP, MySQL, and vanilla CSS, making it easy to deploy on any standard LAMP/LEMP stack.

---

## Features

| Feature | Description |
|---------|-------------|
| **User Authentication** | Register, login, logout, and secure password reset. |
| **Mobile Catalog** | View, filter, and search mobiles; browse by category. |
| **Review System** | Authenticated users can submit, edit, and delete reviews. |
| **Comparison Tool** | Select up to two devices to view a side‑by‑side spec comparison. |
| **Admin Panel** | Manage categories, mobiles, and moderate reviews. |
| **Responsive UI** | Simple, clean layout powered by `css/style.css`. |
| **Contact Support** | Form for users to reach out to the showroom team. |

---

## Tech Stack

| Layer | Technology |
|-------|------------|
| **Backend** | PHP 7.4+ |
| **Database** | MySQL (schema in `Database/mobile_db.sql`) |
| **Frontend** | HTML5, CSS3 (custom stylesheet) |
| **Server** | Apache / Nginx (any LAMP/LEMP environment) |
| **Version Control** | Git (GitHub) |

---

## Installation

1. **Clone the repository**

   ```bash
   git clone https://github.com/yourusername/Mobile_Showroom_final.git
   cd Mobile_Showroom_final
   ```

2. **Set up the database**

   * Create a MySQL database (e.g., `mobile_showroom`).
   * Import the schema:

   ```bash
   mysql -u root -p mobile_showroom < Database/mobile_db.sql
   ```

3. **Configure the application**

   * Copy `config.php.example` to `config.php` (if an example exists) or edit `config.php` directly.
   * Update the DB credentials:

   ```php
   // config.php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'mobile_showroom');
   define('DB_USER', 'YOUR_DB_USERNAME');
   define('DB_PASS', 'YOUR_DB_PASSWORD');
   ```

   * For any external services (e.g., email), replace placeholder keys with `YOUR_OWN_API_KEY`.

4. **Set file permissions**

   ```bash
   chmod -R 755 admin/uploads
   ```

5. **Deploy**

   * Place the project in your web server’s document root (e.g., `/var/www/html`).
   * Ensure PHP is enabled and the `pdo_mysql` extension is installed.

6. **Optional – Composer (if future dependencies are added)**

   ```bash
   composer install
   ```

---

## Usage

### Public Site

* Visit `http://your-domain.com/` – the landing page (`index.php`).
* Browse mobiles, view details, and compare devices via `compare_mobiles.php`.
* Register