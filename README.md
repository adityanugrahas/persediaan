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

## ⚙️ Installation

### 1. Requirements
*   Web Server (Nginx / Apache)
*   PHP 7.4 or higher
*   PostgreSQL (Recommended) or MySQL
*   `php-pdo-pgsql` or `php-pdo-mysql` extension

### 2. Database Setup
1.  Create a new database (e.g., `db_persediaan`).
2.  Import the schema:
    ```bash
    psql -U your_user -d db_persediaan -f schema_pg.sql
    ```

### 3. Configuration
1.  Navigate to `global/koneksi.php`.
2.  Update your database credentials:
    ```php
    $host = 'localhost';
    $db   = 'db_persediaan';
    $user = 'your_username';
    $pass = 'your_password';
    ```

### 4. Permissions
Ensure the following directories are writable by the web server:
*   `img/barang/`
*   `img/users/`
*   `img/bmn/`

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
