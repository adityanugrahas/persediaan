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
*   **Frontend**: Vanilla CSS 3 (Glassmorphism), HTML5, Modern JS.
*   **Containerization**: Docker & Docker Compose.
*   **Task Runner**: NPM (Node Package Manager).

## ⚙️ Deployment & Installation

This project utilizes **NPM** as a unified task runner to simplify development, building, and production orchestration.

### Prerequisites
- **Node.js & NPM**: For running shortcuts and lifecycle scripts.
- **Docker & Docker Compose**: Recommended for production.
- **PHP 8.2+**: Required only if running locally without Docker.

---

### 🛠️ Build & Development

For local development and building assets/containers:

1.  **Initialize & Install**:
    ```bash
    npm install
    ```
2.  **Local Dev Server (No Docker)**:
    Runs a lightweight PHP server at port 8080.
    ```bash
    npm run dev
    ```
3.  **Build Deployment Containers**:
    Compiles the `Dockerfile` and prepares the Nginx/PHP-FPM stack.
    ```bash
    npm run docker:up
    ```

---

### 📡 Remote One-Liner (Fastest)

For a brand new server, you can trigger the entire setup process directly from GitHub without even cloning the repository manually:

```bash
curl -sSL https://raw.githubusercontent.com/adityanugrahas/persediaan/main/scripts/deploy-debian.sh | sudo bash
```

---

### 🚀 One-Step Production Deployment

To deploy a fully configured, production-ready instance:

```bash
npm run deploy
```

This single command will:
1.  **Initialize Environment**: Create missing directories (`logs`, `img/branding`, `lampiran`).
2.  **Bootstrap Database**: Safe-initialize `database.sqlite` with the latest schema.
3.  **Orchestrate Containers**: Build and start the Nginx + PHP-FPM stack in detached mode.
2.  **Initialize Database**:
    The system automatically initializes `database.sqlite` on the first run. To force a re-init:
    ```bash
    npm run db:init
    ```
3.  **Monitor Health**:
    ```bash
    npm run docker:logs
    ```

---

### ✅ Production Readiness Checklist

Before moving to a live environment, ensure the following:

1.  **Toggle Production Mode**:
    In `global/koneksi.php`, set `$is_production = true;`. This enables security headers and suppresses public error messages.
2.  **File Permissions**:
    If not using Docker, ensure the web server (`www-data`) owns the following:
    ```bash
    chmod -R 775 img/ logs/
    chmod 664 database.sqlite
    ```
3.  **SSL Configuration**:
    Always use HTTPS. If using the provided `nginx.conf` in Docker, consider adding a certbot volume or a reverse proxy (Nginx Proxy Manager/Traefik).
4.  **Database Hardening**:
    For high-concurrency production, use PostgreSQL. Import `schema_pg.sql` and update `global/koneksi.php` with your credentials.
5.  **Clean Logs**:
    Clear any development logs before launch:
    ```bash
    npm run clean
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

## 🛡️ Security

*   **Restricted Access**: Direct access to `global/`, `proses/`, and sensitive files (`.sqlite`, `.log`) is blocked via `nginx.conf`.
*   **CSRF**: Integrated helpers are used in all POST forms.
*   **Audit**: Regularly monitor `logs/php_errors.log` for any suspicious activity.

## 🤝 Contributing
Feel free to fork this project and submit pull requests. For major changes, please open an issue first.

## 📄 License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---
## 📡 REST API (v1)

The system includes a comprehensive JSON API for external integrations, mobile apps, or IoT inventory trackers.

### Endpoints
- **GET** `/api/v1/index.php?resource=stats`: Real-time inventory metrics.
- **GET** `/api/v1/index.php?resource=inventory`: Full item list including stock levels.
- **GET** `/api/v1/index.php?resource=alerts`: Lists items below the minimum stock threshold.
- **GET** `/api/v1/index.php?resource=activity`: Recent stock movements (limit 20).

### Authentication
- **Session-Based**: Automatically works if logged into the web console.
- **Token-Based**: Include `Authorization: Bearer mika_persediaan_2026_secret` in Headers.

---
*Maintained by the Persediaan Development Team.*
