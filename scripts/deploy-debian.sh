#!/bin/bash
# ============================================================================
# Persediaan System: Comprehensive Debian/Ubuntu Deployment Automator
# Target: Production Linux Server (Debian 11+, Ubuntu 20.04+)
# Status: Production-Ready
# ============================================================================

set -e # Exit on error

# Ensure script is run as root
if [ "$EUID" -ne 0 ]; then
  echo "❌ Please run as root (sudo bash scripts/deploy-debian.sh)"
  exit 1
fi

echo "🚀 Starting Comprehensive Deployment for Persediaan System..."

# 1. GitHub Repository Context
REPO_URL="https://github.com/adityanugrahas/persediaan.git"
TARGET_DIR="/var/www/persediaan"

if [ ! -d ".git" ]; then
    echo "📡 Cloning repository from $REPO_URL..."
    apt-get update && apt-get install -y git
    git clone $REPO_URL $TARGET_DIR
    cd $TARGET_DIR
else
    echo "✅ Running from inside a localized repository."
fi

# 2. Update & Prerequisite Installation
echo "📦 Updating system packages..."
apt-get update && apt-get upgrade -y
apt-get install -y curl gnupg2 lsb-release ca-certificates apt-transport-https nginx

# 2. Docker Installation
if ! command -v docker &> /dev/null; then
    echo "🐳 Installing Docker Engine..."
    curl -fsSL https://get.docker.com -o get-docker.sh
    sh get-docker.sh
    systemctl enable --now docker
else
    echo "✅ Docker is already installed."
fi

# 3. Docker Compose Installation (Plugin)
if ! docker compose version &> /dev/null; then
    echo "🏗️ Installing Docker Compose plugin..."
    apt-get install -y docker-compose-plugin
else
    echo "✅ Docker Compose is already installed."
fi

# 4. Node.js & NPM Installation (v18+)
if ! command -v npm &> /dev/null; then
    echo "🟢 Installing Node.js & NPM..."
    curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
    apt-get install -y nodejs
else
    echo "✅ Node.js/NPM is already installed."
fi

# 5. Core Environment Preparation
echo "📂 Setting up project directories and permissions..."
mkdir -p logs img/branding lampiran
chown -R www-data:www-data .
chmod -R 775 img/ logs/

# 6. Database Initialization
echo "🗄️ Initializing and Seeding Production Database..."
npm run setup
npm run db:init

# 7. Production Orchestration
echo "⚡ Building and Starting Production Clusters..."
npm run production

# 8. Host-Level Nginx Reverse Proxy Setup
echo "🌐 Configuring Comprehensive Nginx Reverse Proxy (Port 80 -> 8080)..."
cat > /etc/nginx/sites-available/persediaan <<EOF
server {
    listen 80;
    server_name _;

    location / {
        proxy_pass http://localhost:8080;
        proxy_set_header Host \$host;
        proxy_set_header X-Real-IP \$remote_addr;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto \$scheme;
    }

    client_max_body_size 10M;
}
EOF

ln -sf /etc/nginx/sites-available/persediaan /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
systemctl restart nginx

# 9. Firewall Configuration (UFW)
if command -v ufw &> /dev/null; then
    echo "🛡️ Configuring Firewall (UFW)..."
    ufw allow 80/tcp
    ufw allow 8080/tcp
    ufw allow ssh
    echo "✅ Port 80 and 8080 opened."
fi

echo "============================================================================"
echo "🎉 COMPREHENSIVE DEPLOYMENT SUCCESSFUL!"
echo "============================================================================"
echo "System is live at: http://$(curl -s ifconfig.me)"
echo "Admin Access: admin / admin"
echo "============================================================================"
