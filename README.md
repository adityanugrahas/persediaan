# Persediaan — Modern Inventory & Asset Management System

Persediaan is a robust, secure, and modern web application built for seamless tracking of consumable stock and **Barang Milik Negara (BMN)**. Originally built with legacy procedural PHP, it has been fully rebuilt/migrated to **PHP PDO** with a premium **Glassmorphism UI** for a state-of-the-art user experience.

![Dashboard Preview](https://via.placeholder.com/800x400/1a1a2e/ffffff?text=Persediaan+Glassmorphism+UI)

## 🚀 Key Features

*   **📦 Stock Tracking**: Real-time monitoring of consumable items with low-stock alerts.
*   **📡 BMN Management**: Comprehensive tracking of government assets (Barang Milik Negara) with distribution and return history.
*   **📑 Reporting**: Generate detailed rekapitulasi of usage, mutations, and unit-based requests.
*   **🔗 Approval Workflow**: Built-in request system where units (Seksi/Bidang) can propose item usage for admin approval.
*   **🎨 Premium Glassmorphism UI**: A stunning, responsive interface with frosted glass effects, animated backgrounds, and dark-mode aesthetics.
*   **🔐 Enterprise-Grade Security**:
    *   **PDO Prepared Statements**: 100% protection against SQL Injection.
    *   **Password Hashing**: Secure storage using `bcrypt`.
    *   **XSS Protection**: Complete output escaping for all dynamic data.
    *   **CSRF Ready**: Built-in helpers for cross-site request forgery protection.

## 🛠️ Technology Stack

*   **Backend**: PHP 7.4 / 8.x
*   **Database**: PostgreSQL (Primary) / MySQL compatible
*   **Architecture**: PDO (PHP Data Objects) with centralized database helpers
*   **Frontend**: Vanilla CSS 3 (Dynamic Variables), HTML5, JavaScript
*   **Icons**: Font Awesome 5 (SVG & Fonts)
*   **Layout**: Customized Bootstrap 4 with Glassmorphism Overrides

## 📂 Project Structure

```text
├── ajax/               # Dynamic modal content and partial updates
├── cetak/              # PDF and Print slip generation logic
├── content/            # Core page modules (Stock, BMN, Reports)
├── css/                # Custom design system (custom.css)
├── global/             # Connection settings and core helpers (koneksi.php)
├── img/                # Asset storage (barang, users, icons)
├── js/                 # Application logic and UI interactivity
├── proses/             # Backend transactional logic (CRUD, Auth)
├── index.php           # Main application entry (Layout & Router)
└── schema_pg.sql       # PostgreSQL database schema & seeds
```

## ⚙️ Deployment & Installation Guide

This section provides a detailed walkthrough for deploying the application on a **Debian/Ubuntu** server using a **LEMP** (Linux, Nginx, PostgreSQL, PHP) stack.

### 1. Server Preparation
Update your system and install the required packages:
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx postgresql php-fpm php-pdo-pgsql php-gd php-curl php-mbstring git
```

### 2. Database Configuration (PostgreSQL)
1. **Switch to Postgres user:** `sudo -u postgres psql`
2. **Setup User & DB:**
   ```sql
   CREATE DATABASE db_persediaan;
   CREATE USER pt_user WITH ENCRYPTED PASSWORD 'your_secure_password';
   GRANT ALL PRIVILEGES ON DATABASE db_persediaan TO pt_user;
   \q
   ```
3. **Import Schema:**
   ```bash
   psql -U pt_user -d db_persediaan -h localhost -f schema_pg.sql
   ```

### 3. Application Setup
1. **Clone/Upload** the files to `/var/www/persediaan`.
2. **Configure Database**: Edit `global/koneksi.php` with your credentials:
   ```php
   $host = 'localhost';
   $db   = 'db_persediaan';
   $user = 'pt_user';
   $pass = 'your_secure_password';
   ```
3. **Permissions**:
   ```bash
   sudo chown -R www-data:www-data /var/www/persediaan
   sudo chmod -R 755 /var/www/persediaan
   sudo chmod -R 775 /var/www/persediaan/img/
   ```

### 4. Nginx Site Configuration
Create `/etc/nginx/sites-available/persediaan`:
```nginx
server {
    listen 80;
    server_name your_domain_or_ip;
    root /var/www/persediaan;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.x-fpm.sock;
    }
}
```
Enable the site: `sudo ln -s /etc/nginx/sites-available/persediaan /etc/nginx/sites-enabled/ && sudo systemctl restart nginx`

## 🛡️ Security Hardening
*   **SSL**: Always use `certbot` for HTTPS: `sudo certbot --nginx`.
*   **App Logic**: directe access to `global/` and `proses/` is restricted via index logic.
*   **Env**: Ensure `display_errors` is `Off` in production `php.ini`.

## 🛡️ Security Best Practices
*   **Production Move**: Disable `display_errors` in your `php.ini`.
*   **SSL**: Always deploy with HTTPS enabled.
*   **Directory Access**: Configure Nginx/Apache to deny web access to the `global/` and `proses/` directories directly.

## 🤝 Contributing
Feel free to fork this project and submit pull requests for any features or UI improvements. For major changes, please open an issue first to discuss what you would like to change.

## 📄 License
This project is licensed under the MIT License - see the LICENSE file for details.

---
*Maintained by the Persediaan Development Team.*
