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
apt-get install -y curl gnupg2 lsb-release ca-certificates apt-transport-https

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

# 8. Firewall Configuration (UFW)
if command -v ufw &> /dev/null; then
    echo "🛡️ Configuring Firewall (UFW)..."
    ufw allow 8080/tcp
    ufw allow ssh
    echo "✅ Port 8080 opened. Ensure your cloud provider (AWS/DigitalOcean/GCP) security groups allow 8080."
fi

echo "============================================================================"
echo "🎉 DEPLOYMENT SUCCESSFUL!"
echo "============================================================================"
echo "System is live at: http://$(curl -s ifconfig.me):8080"
echo "To monitor logs: npm run docker:logs"
echo "============================================================================"
