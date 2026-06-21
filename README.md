# a-amusement server
Where the project files for the main a-amusement server live!

## Pre-requisites
* A Linux webhost
* PHP 8.4
* PostgreSQL

## Instructions
### 1. Server Requirements

Prepare the pre-req's as shown above

### 2. Upload the Application

Upload all project files to your server, e.g. /var/www/a-amusement (or your host's public_html/web root).
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
sudonano into config.php and set up your database and set up your database. It's usually not nessecary to edit the port on most occasions

After this run setup.php to configure the databases! 
And just like that, you now have a-amusement!
