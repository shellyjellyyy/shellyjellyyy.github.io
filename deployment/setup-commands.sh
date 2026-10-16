#!/bin/bash
# =============================================================================
# GameAssetLab - VPS Deployment Script
# CSET489 SEO Mini-Project - Milestone I Deployment
#
# USAGE:
#   Run each numbered step manually in sequence, or adapt as a provisioning
#   script. Review every command before running. This script targets a fresh
#   Ubuntu 22.04 LTS VPS with no pre-existing web server.
#
# SECURITY:
#   - Never paste passwords, private keys, or API tokens into this file.
#   - Replace every <PLACEHOLDER> with the actual value at runtime.
#   - Store secrets in a password manager, not in version control.
#
# Target stack: Ubuntu 22.04 LTS · Nginx · PHP 8.2-FPM · MySQL 8 · WordPress 6.7
# WP-CLI and Certbot are installed during the script.
# =============================================================================

# IMPORTANT: Do NOT run this entire file as a single script with `bash setup-commands.sh`.
# Step 4 (mysql_secure_installation) is interactive. Run each section manually.
# If you must automate Step 4, replace it with the inline MySQL commands provided.

# Exit immediately on error, unset variable, or pipe failure - remove if running
# step-by-step, since some commands (e.g. wp plugin delete) may exit non-zero.
set -uo pipefail

# ─── REQUIRED VARIABLES ──────────────────────────────────────────────────────
# Set these before running any steps.
DOMAIN="<YOUR_DOMAIN>"           # e.g. gameassetlab.com - no https://, no trailing slash
DB_NAME="gameassetlab_db"
DB_USER="gal_user"
DB_PASS="<STRONG_DB_PASSWORD>"   # Replace with a randomly generated password (20+ chars)
WP_ROOT="/var/www/gameassetlab"
ADMIN_EMAIL="<YOUR_ADMIN_EMAIL>"
# Do NOT set ADMIN_PASS here. Enter it interactively at Step 9.

# ─── STEP 1: Initial server update ───────────────────────────────────────────
echo "=== 1. Update and secure the server ==="
sudo apt update && sudo apt upgrade -y
sudo apt install -y ufw fail2ban unzip curl wget gnupg

# Firewall: allow SSH, HTTP, HTTPS
sudo ufw allow OpenSSH
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw --force enable
sudo ufw status

# ─── STEP 2: Install Nginx ───────────────────────────────────────────────────
echo "=== 2. Install Nginx ==="
sudo apt install -y nginx
sudo systemctl enable nginx
sudo systemctl start nginx
# Verify
sudo nginx -t

# ─── STEP 3: Install PHP 8.2 + required extensions ──────────────────────────
echo "=== 3. Install PHP 8.2 ==="
sudo apt install -y software-properties-common
sudo add-apt-repository -y ppa:ondrej/php
sudo apt update
sudo apt install -y \
  php8.2-fpm php8.2-mysql php8.2-curl php8.2-gd php8.2-mbstring \
  php8.2-xml php8.2-zip php8.2-intl php8.2-bcmath php8.2-imagick

php8.2 --version   # Verify
sudo systemctl enable php8.2-fpm

# PHP-FPM config - increase limits for WordPress
sudo sed -i 's/^upload_max_filesize = .*/upload_max_filesize = 64M/' /etc/php/8.2/fpm/php.ini
sudo sed -i 's/^post_max_size = .*/post_max_size = 64M/' /etc/php/8.2/fpm/php.ini
sudo sed -i 's/^memory_limit = .*/memory_limit = 256M/' /etc/php/8.2/fpm/php.ini
sudo sed -i 's/^max_execution_time = .*/max_execution_time = 120/' /etc/php/8.2/fpm/php.ini

sudo systemctl restart php8.2-fpm

# ─── STEP 4: Install and harden MySQL 8 ──────────────────────────────────────
echo "=== 4. Install and configure MySQL ==="
sudo apt install -y mysql-server
sudo systemctl enable mysql
sudo systemctl start mysql

# Option A (recommended for automation): harden MySQL without the interactive wizard.
# Run these commands instead of mysql_secure_installation.
sudo mysql -u root <<EOF
ALTER USER 'root'@'localhost' IDENTIFIED WITH 'caching_sha2_password' BY '${DB_PASS}_root';
DELETE FROM mysql.user WHERE User='';
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');
DROP DATABASE IF EXISTS test;
DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';
FLUSH PRIVILEGES;
EOF

# Option B (interactive, manual only): run mysql_secure_installation separately
# in a terminal session before continuing this script. Do not include it in
# an automated pipeline - it will block and cause set -e to abort.
# sudo mysql_secure_installation

# Create WordPress database and user
sudo mysql -u root -p"${DB_PASS}_root" <<EOF
CREATE DATABASE IF NOT EXISTS ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
SHOW DATABASES;
EOF

# ─── STEP 5: Download and configure WordPress ────────────────────────────────
echo "=== 5. Install WordPress ==="
sudo mkdir -p "${WP_ROOT}"
sudo chown -R "${USER}:${USER}" "${WP_ROOT}"

cd "${WP_ROOT}"
wget https://wordpress.org/latest.tar.gz
tar -xzf latest.tar.gz
mv wordpress/* .
rm -rf wordpress latest.tar.gz

cp wp-config-sample.php wp-config.php

# Set database credentials in wp-config.php
sed -i "s/database_name_here/${DB_NAME}/" wp-config.php
sed -i "s/username_here/${DB_USER}/" wp-config.php
sed -i "s/password_here/${DB_PASS}/" wp-config.php
sed -i "s/localhost/localhost/" wp-config.php

# Inject WordPress secret keys (fetched live from the API)
WP_KEYS=$(curl -fsSL https://api.wordpress.org/secret-key/1.1/salt/)
# Replace the placeholder key block between the first and last AUTH_KEY lines:
python3 -c "
import re, sys
keys = open('/dev/stdin').read()
config = open('wp-config.php').read()
config = re.sub(
  r\"define\('AUTH_KEY'.*define\('NONCE_SALT'[^\)]+\);\",
  keys.strip(),
  config,
  flags=re.DOTALL
)
open('wp-config.php','w').write(config)
print('Keys injected.')
" <<< "${WP_KEYS}"

# Set file permissions - owner www-data, no world-write
sudo chown -R www-data:www-data "${WP_ROOT}"
sudo find "${WP_ROOT}" -type d -exec chmod 755 {} \;
sudo find "${WP_ROOT}" -type f -exec chmod 644 {} \;
# wp-config.php must be readable only by www-data
sudo chmod 600 "${WP_ROOT}/wp-config.php"

# ─── STEP 6: Upload the GameAssetLab theme ───────────────────────────────────
echo "=== 6. Upload and install the theme ==="
# From your LOCAL machine (not the server), run:
#   scp gameassetlab-theme.zip <USER>@<SERVER_IP>:~
# Then on the server:
# sudo unzip ~/gameassetlab-theme.zip -d "${WP_ROOT}/wp-content/themes/gameassetlab-theme"
# OR copy the expanded directory:
#   scp -r ./gameassetlab-theme <USER>@<SERVER_IP>:"${WP_ROOT}/wp-content/themes/"

sudo chown -R www-data:www-data "${WP_ROOT}/wp-content/themes/"
sudo find "${WP_ROOT}/wp-content/themes/" -type d -exec chmod 755 {} \;
sudo find "${WP_ROOT}/wp-content/themes/" -type f -exec chmod 644 {} \;

# ─── STEP 7: Configure Nginx virtual host ────────────────────────────────────
echo "=== 7. Configure Nginx ==="
sudo tee "/etc/nginx/sites-available/gameassetlab" <<NGINX
server {
    listen 80;
    listen [::]:80;
    server_name ${DOMAIN} www.${DOMAIN};

    root ${WP_ROOT};
    index index.php index.html;

    # WordPress permalink support - pass all non-file requests to WordPress
    location / {
        try_files \$uri \$uri/ /index.php?\$args;
    }

    # PHP-FPM processing
    location ~ \.php\$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 120;
    }

    # Security headers
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # Block access to sensitive files and directories
    location ~ /\. { deny all; return 404; }
    location ~* /wp-config\.php { deny all; return 404; }
    location ~* /(?:uploads|files)/.*\.php$ { deny all; return 404; }
    location = /xmlrpc.php { deny all; return 404; access_log off; log_not_found off; }

    # Static asset caching (immutable for versioned assets)
    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|webp|woff2|ttf|otf)$ {
        expires 30d;
        add_header Cache-Control "public, max-age=2592000";
        access_log off;
    }

    client_max_body_size 64M;
    access_log /var/log/nginx/gameassetlab.access.log;
    error_log  /var/log/nginx/gameassetlab.error.log warn;
}
NGINX

sudo ln -sf /etc/nginx/sites-available/gameassetlab /etc/nginx/sites-enabled/gameassetlab
# Remove default site only if it exists
[ -f /etc/nginx/sites-enabled/default ] && sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t && sudo systemctl reload nginx

# ─── STEP 8: SSL via Let's Encrypt / Certbot ─────────────────────────────────
echo "=== 8. SSL with Certbot ==="
# DNS for ${DOMAIN} must be pointing to this server's IP before running certbot.
# Verify: dig +short ${DOMAIN} should return this server's IP.
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d "${DOMAIN}" -d "www.${DOMAIN}" \
  --non-interactive --agree-tos --email "${ADMIN_EMAIL}" \
  --redirect

# Verify auto-renewal (dry run only - does not renew)
sudo certbot renew --dry-run

# ─── STEP 9: Install WP-CLI ──────────────────────────────────────────────────
echo "=== 9. Install WP-CLI ==="
curl -fsSL -o /tmp/wp-cli.phar https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
php8.2 /tmp/wp-cli.phar --info
chmod +x /tmp/wp-cli.phar
sudo mv /tmp/wp-cli.phar /usr/local/bin/wp

# ─── STEP 10: WordPress core install via WP-CLI ──────────────────────────────
echo "=== 10. WordPress setup ==="
cd "${WP_ROOT}"

# NOTE: --admin_password is intentionally omitted here.
# WP-CLI will prompt for it. Do not store passwords in scripts.
wp core install \
  --url="https://${DOMAIN}" \
  --title="GameAssetLab - Blender Game Asset Tutorials" \
  --admin_user="admin" \
  --admin_email="${ADMIN_EMAIL}" \
  --skip-email \
  --allow-root
# Enter admin password when prompted.

# Set permalink structure - matches the intended URL pattern
# /tutorials/<category-slug>/<post-slug>/
wp rewrite structure '/tutorials/%tutorial_cat%/%postname%/' --allow-root
wp rewrite flush --allow-root

# Activate the GameAssetLab theme
wp theme activate gameassetlab-theme --allow-root

# ─── STEP 11: Install and activate plugins ───────────────────────────────────
echo "=== 11. Install plugins ==="
# Rank Math SEO - handles sitemap, schema, meta titles, Open Graph
wp plugin install seo-by-rank-math --activate --allow-root

# WP Super Cache - page caching for performance
wp plugin install wp-super-cache --activate --allow-root

# UpdraftPlus - backups (required by CSET489 handbook)
wp plugin install updraftplus --activate --allow-root

# Remove unused default content
wp plugin delete hello --allow-root || true
wp plugin delete akismet --allow-root || true
wp theme delete twentytwentythree --allow-root || true
wp theme delete twentytwentyfour --allow-root || true

# ─── STEP 12: Create tutorial taxonomy categories ────────────────────────────
echo "=== 12. Create tutorial categories ==="
wp term create tutorial_cat "Low-Poly Modeling" \
  --slug="low-poly-modeling" \
  --description="Step-by-step tutorials for modeling low-poly props in Blender, including geometry decisions, edge loops, and polygon budget." \
  --allow-root

wp term create tutorial_cat "UV Mapping & Texturing" \
  --slug="uv-texturing" \
  --description="Tutorials for UV unwrapping game assets in Blender, including seam placement, UV islands, and texture layout." \
  --allow-root

wp term create tutorial_cat "Game Engine Export" \
  --slug="game-engine-export" \
  --description="Tutorials for exporting Blender assets into game engines including Unity FBX export settings and common import errors." \
  --allow-root

wp term create difficulty "Beginner" --slug="beginner" --allow-root
wp term create difficulty "Intermediate" --slug="intermediate" --allow-root

# ─── STEP 13: Create required WordPress pages ────────────────────────────────
echo "=== 13. Create pages ==="

# Home page - used with front-page.php (WordPress loads it when front page is set to a page)
HOME_ID=$(wp post create \
  --post_type=page \
  --post_title="GameAssetLab - Blender Game Asset Tutorials" \
  --post_status=publish \
  --post_name="home" \
  --porcelain \
  --allow-root)
echo "Home page ID: ${HOME_ID}"

# Set reading settings: static front page
wp option update show_on_front page --allow-root
wp option update page_on_front "${HOME_ID}" --allow-root

# Tutorials Hub page - assign the "Tutorials Hub" page template
TUTORIALS_ID=$(wp post create \
  --post_type=page \
  --post_title="Tutorials" \
  --post_status=publish \
  --post_name="tutorials" \
  --porcelain \
  --allow-root)
echo "Tutorials page ID: ${TUTORIALS_ID}"
# Set the page template (meta key _wp_page_template)
wp post meta update "${TUTORIALS_ID}" _wp_page_template "page-templates/page-tutorials.php" --allow-root

# About page - assign the "About" page template
ABOUT_ID=$(wp post create \
  --post_type=page \
  --post_title="About GameAssetLab" \
  --post_status=publish \
  --post_name="about" \
  --porcelain \
  --allow-root)
echo "About page ID: ${ABOUT_ID}"
wp post meta update "${ABOUT_ID}" _wp_page_template "page-templates/page-about.php" --allow-root

# ─── STEP 14: Create tutorials from content templates ───────────────────────
echo "=== 14. Import tutorial content ==="
# The three tutorials in /content-templates/ should be pasted into the WordPress
# block editor manually, or imported via WP-CLI using the commands below.
# The markdown frontmatter (Title, Slug, Category, etc.) is NOT part of the post content -
# paste only the content below "FULL CONTENT (paste into editor):" for each file.

# Example for Tutorial 1 (run manually after reviewing content):
# BARREL_ID=$(wp post create \
#   --post_type=tutorial \
#   --post_title="Low-Poly Barrel in Blender: A Step-by-Step Tutorial" \
#   --post_name="low-poly-barrel-blender" \
#   --post_status=publish \
#   --post_excerpt="Model a game-ready barrel from scratch using Blender's basic tools." \
#   --porcelain \
#   --allow-root)
# wp post term set "${BARREL_ID}" tutorial_cat low-poly-modeling --allow-root
# wp post term set "${BARREL_ID}" difficulty beginner --allow-root
# wp post meta update "${BARREL_ID}" _gal_read_time "45" --allow-root
# wp post meta update "${BARREL_ID}" _gal_software "Blender 4.x" --allow-root
# wp post meta update "${BARREL_ID}" _gal_blender_version "4.2 LTS" --allow-root
# wp post meta update "${BARREL_ID}" _gal_next_tutorial "/tutorials/uv-texturing/blender-uv-unwrapping-game-asset/" --allow-root

# ─── STEP 15: Post-install flush ─────────────────────────────────────────────
echo "=== 15. Flush rewrite rules ==="
wp rewrite flush --allow-root
# Also visit Settings → Permalinks in the admin and click Save Changes.

# ─── STEP 16: Rank Math configuration (manual) ───────────────────────────────
echo "=== 16. Rank Math - manual configuration required ==="
echo ""
echo "After logging in to WordPress admin:"
echo "  1. Go to Rank Math → Setup Wizard → follow the wizard"
echo "  2. Enable sitemap: Rank Math → Sitemap"
echo "  3. Verify sitemap at: https://${DOMAIN}/sitemap_index.xml"
echo "  4. Submit sitemap in Google Search Console"
echo "  5. Verify site ownership in Search Console"
echo "  6. Enable Google Analytics 4 via Rank Math or site kit plugin"
echo ""

# ─── STEP 17: Deployment verification ────────────────────────────────────────
echo ""
echo "=== DEPLOYMENT VERIFICATION CHECKLIST ==="
echo ""
echo "Run each command and record the actual output as evidence:"
echo ""
echo "[ ] curl -sI https://${DOMAIN}/ | head -5"
echo "    Expected: HTTP/2 200"
echo ""
echo "[ ] curl -sI http://${DOMAIN}/ | head -5"
echo "    Expected: HTTP/1.1 301, Location: https://${DOMAIN}/"
echo ""
echo "[ ] curl -sI https://${DOMAIN}/tutorials/ | head -5"
echo "    Expected: HTTP/2 200"
echo ""
echo "[ ] curl -sI https://${DOMAIN}/about/ | head -5"
echo "    Expected: HTTP/2 200"
echo ""
echo "[ ] curl -s https://${DOMAIN}/sitemap_index.xml | head -20"
echo "    Expected: XML sitemap index from Rank Math"
echo ""
echo "[ ] curl -s https://${DOMAIN}/robots.txt"
echo "    Expected: User-agent: *, Disallow: /wp-admin/, Sitemap: URL"
echo ""
echo "[ ] sudo certbot certificates"
echo "    Expected: Certificate for ${DOMAIN}, expiry date shown"
echo ""
echo "[ ] wp option get siteurl --allow-root"
echo "    Expected: https://${DOMAIN}"
echo ""
echo "[ ] wp option get blogname --allow-root"
echo "    Expected: GameAssetLab"
echo ""
echo "[ ] wp theme status --allow-root"
echo "    Expected: gameassetlab-theme Active"
echo ""
echo "[ ] wp plugin list --status=active --allow-root"
echo "    Expected: seo-by-rank-math, wp-super-cache, updraftplus listed as Active"
echo ""
echo "=== DEPLOYMENT COMPLETE ==="
echo "Save all command outputs as screenshots or text files for the Milestone I evidence folder."
