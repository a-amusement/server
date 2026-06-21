# a-amusement server
Where the project files for the main a-amusement server live!

## Pre-requisites
* A Linux webhost
* PHP 8.4
* PostgreSQL

## Instructions
### 1. Server Requirements

PHP 8.0+ (with pdo_pgsql and pgsql extensions enabled)
PostgreSQL 13+ server (can be on the same host or a managed DB service)
Web server: Apache (with mod_php or PHP-FPM) or Nginx + PHP-FPM
Composer (if the app uses any PHP dependencies)

### 2. Upload the Application

Upload all project files to your hosting account, e.g. /var/www/a-amusement (or your host's public_html/web root).
Ensure the web server's document root points to the app's public folder (or wherever index.php lives).
Set correct file permissions:

   chown -R www-data:www-data /var/www/a-amusement
   find /var/www/a-amusement -type d -exec chmod 755 {} \;
   find /var/www/a-amusement -type f -exec chmod 644 {} \;
### 3. Create the PostgreSQL Database

On the host (or via your hosting control panel's database tool):

sudo -u postgres psql
CREATE DATABASE aamusement;
CREATE USER aamusement_user WITH ENCRYPTED PASSWORD 'choose-a-strong-password';
GRANT ALL PRIVILEGES ON DATABASE aamusement TO aamusement_user;
\q

### 4. Configure Database Connection
Find the app's config file (commonly .env, config.php, or similar) and set:
DB_HOST=localhost
DB_PORT=5432
DB_NAME=aamusement
DB_USER=aamusement_user
DB_PASSWORD=choose-a-strong-password
5. Run the Provisioning Script at setup.php


Use Let's Encrypt (certbot) or your host's SSL tool to enable HTTPS.
