# Persediaan — Modern Inventory & Asset Management System

**v2.0 Production Ready — Glassmorphism Edition**

Persediaan is a robust, secure, and modern web application built for seamless tracking of consumable stock and **Barang Milik Negara (BMN)**. Originally built with legacy procedural PHP, it has been fully rebuilt/migrated to **PHP PDO** with a premium **Glassmorphism UI** for a state-of-the-art user experience.

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

*   **Backend**: PHP 8.2+ (PDO-based)
*   **Database**: SQLite (Default) / Support for PostgreSQL & MySQL schemas included.
*   **Frontend**: Vanilla CSS 3 with Glassmorphism Design System, HTML5, Modern JS.
*   **Containerization**: Docker & Docker Compose.
*   **Task Runner**: NPM (Node Package Manager).

## ⚙️ Deployment & Installation

This project utilizes **NPM** as a task runner to simplify both local development and Docker orchestration.

### Prerequisites
- **Node.js & NPM**: Required for running shortcuts.
- **PHP 8.2+**: Required for local server (Option A).
- **Docker & Docker Compose**: Required for containerized deployment (Option B).

---

### Option A: Local Development (NPM + PHP)
Best for quick testing or UI modifications without Docker.

1.  **Initialize Project**:
    ```bash
    npm install
    ```
2.  **Start PHP Development Server**:
    ```bash
    npm run dev
    ```
3.  **Access**: Open [http://localhost:8080](http://localhost:8080) in your browser.

---

### Option B: Docker Deployment (Recommended)
Best for consistent environments and production-like testing.

1.  **Build and Start Containers**:
    This will spin up two containers: `persediaan-app` (PHP-FPM) and `persediaan-web` (Nginx).
    ```bash
    npm run docker:up
    ```
2.  **Access**: Open [http://localhost:8080](http://localhost:8080).
3.  **Manage Environment**:
    -   **View Logs**: `npm run docker:logs`
    -   **Rebuild Image**: `npm run docker:rebuild`
    -   **Stop Containers**: `npm run docker:down`

---

### Option C: Manual Production Deployment (LEMP)
The system is optimized for **LEMP** (Linux, Nginx, PostgreSQL/SQLite, PHP) stacks.

1.  **Install Required PHP Extensions**:
    `pdo_sqlite` (or `pdo_pgsql`), `gd`, `curl`, `mbstring`, `intl`, `zip`.
2.  **Set Permissions**:
    Ensure the web server has write access:
    ```bash
    sudo chown -R www-data:www-data /var/www/persediaan
    sudo chmod -R 775 /var/www/persediaan/img/
    sudo chmod -R 775 /var/www/persediaan/logs/
    sudo chmod 664 /var/www/persediaan/database.sqlite
    ```

## 📂 Project Structure

```text
├── ajax/               # Dynamic modal content and partial updates
├── cetak/              # PDF and Print slip generation logic
├── content/            # Core page modules (Stock, BMN, Reports)
├── css/                # Custom design system with glassmorphism
├── global/             # Connection settings and PDO helpers
├── img/                # Asset storage (barang, users, icons)
├── js/                 # Application logic and UI interactivity
├── proses/             # Backend transactional logic (CRUD, Auth)
├── Dockerfile          # PHP-FPM container definition
├── docker-compose.yml  # Multi-container orchestration
├── nginx.conf          # Nginx configuration for Docker/Production
├── package.json        # NPM script definitions
└── database.sqlite     # Portable SQLite database
```

## 🛡️ Security Hardening

*   **SSL/TLS**: Mandatory for production. Use `certbot` for free Let's Encrypt certificates.
*   **Database Access**: The `nginx.conf` and project logic are pre-configured to block direct access to `.sqlite`, `.sql`, and `.log` files.
*   **Error Reporting**: In production, ensure `display_errors` is set to `Off` in `php.ini`. Errors are automatically logged to the `logs/` directory.

## 🤝 Contributing
Feel free to fork this project and submit pull requests for UI enhancements or additional modules. For major changes, please open an issue first.

## 📄 License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---
*Maintained by the Persediaan Development Team.*
