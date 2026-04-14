# Persediaan — Modern Inventory & Asset Management System
**v2.0 Production Ready — Glassmorphism Edition**

Persediaan is a robust, secure, and modern web application built for seamless tracking of consumable stock and **Barang Milik Negara (BMN)**. The system features a premium **Glassmorphism UI** and a secured **PDO-based architecture**.

![Dashboard Preview](https://via.placeholder.com/800x400/1a1a2e/ffffff?text=Persediaan+Glassmorphism+UI)

## 🚀 Key Features

*   **📦 Stock Tracking**: Real-time monitoring of consumable items with low-stock alerts and history.
*   **📡 BMN Management**: Comprehensive tracking of government assets with distribution history.
*   **📑 Reporting & Analytics**: Dynamic summary widgets and detailed rekapitulasi of usage/mutations.
*   **🔗 Approval Workflow**: Built-in request system where units (Seksi/Bidang) propose item usage.
*   **🎨 Premium Glassmorphism UI**: High-fidelity frosted glass effects, radial gradients, and fluid animations.
*   **🔐 Production-Stage Security**:
    *   **PDO Prepared Statements**: 100% protection against SQL Injection.
    *   **Password Hashing**: Secure storage using `bcrypt`.
    *   **CSRF Protection**: Integrated secondary validation for state-changing requests.
    *   **Automatic Error Logging**: Production-mode error suppression with background logging.

## 🛠️ Technology Stack

*   **Backend**: PHP 8.x (Compatible with PHP 7.4)
*   **Database**: SQLite (Zero-config) / Support for MySQL & PostgreSQL
*   **UI/UX**: Vanilla CSS 3 with Glassmorphism Design System
*   **Layout**: Customized Bootstrap 4 context
*   **Security**: Centralized PDO Helpers & Security Headers

## ⚙️ Deployment & Installation

### Option A: One-Click Docker Deployment (Recommended)
This method requires **Docker** and **Docker Compose**.
1. **Build & Start**:
   ```bash
   npm run docker:up
   ```
2. **Access**: Open `http://localhost:8080` in your browser.
3. **Monitor Logs**: `npm run docker:logs`.

### Option B: Local PHP Development
For quick testing and development:
1. **Start Server**:
   ```bash
   npm run dev
   ```
2. **Access**: Open `http://localhost:8080`.

### Option C: Manual Production Deployment (LEMP/LAMP)
The system is optimized for **LEMP** or **LAMP** stacks.
1. **PHP extensions required**: `pdo_sqlite`, `gd`, `curl`, `mbstring`, `intl`.
2. **Permissions**: Ensure the web server has write access:
   ```bash
   sudo chown -R www-data:www-data /var/www/persediaan
   sudo chmod -R 775 /var/www/persediaan/database.sqlite
   sudo chmod -R 775 /var/www/persediaan/img/
   sudo chmod -R 775 /var/www/persediaan/logs/
   ```

## 🛡️ Security Hardening
*   **SSL/TLS**: Mandatory for production. Use `certbot` for free Let's Encrypt certificates.
*   **Database**: For extremely high-concurrency environments, consider migrating the SQLite data back to PostgreSQL/MySQL via provided `.sql` schemas.
*   **Session Security**: Ensure `session.cookie_httponly` is enabled in `php.ini`.

## 🤝 Contributing
Feel free to fork this project and submit pull requests for UI enhancements or additional modules.

## 📄 License
This project is licensed under the MIT License.

---
*Maintained by the Persediaan Development Team.*
