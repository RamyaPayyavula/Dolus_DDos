#!/bin/bash

#Written by Andrew Krall, 11-02-2018

#This script is used to install and configure AdminUI on the controller for the DDoS Lab 2.

function checkErr() {
    echo -e "${RED}$1 failed. Exiting.${NC}" >&2; exit;
}

#First, set the apache default site to point to /var/www/public_html and restart the apache2 server.

cd /etc/apache2/sites-enabled
sudo sed -i s+var/www/html+var/www/public_html+g 000-default.conf
cd /var/www
sudo mv /var/www/html /var/www/public_html
sudo service apache2 restart

cd /var/www/public_html
sudo rm index.html
sudo echo "<?php echo phpinfo(); ?>" > index.php
cd
sudo apt-get -y install composer || checkErr "The installation of composer"
sudo git clone https://github.com/RamyaPayyavula/Dolus_DDos || checkErr "Attempting to clone the git repository"
cd Dolus_DDos
sudo chmod -R 777 /var/www/
sudo chmod -R 774 /var/www/public_html/Dolus_DDos

sudo apt-get install php-mbstring php-xml
composer update
composer dump-autoload
sudo apt-get install php7.0-zip
composer global require "laravel/installer"
sudo a2enmod rewrite
cd /etc/apache2/sites-enabled
sudo sed -i "s+.*DocumentRoot.*+DocumentRoot /var/www/public_html/Dolus_DDos/public+g" 000-default.conf
#Add the following lines below the DocumentRoot line
cat >> o << EOL

<Directory /var/www/public_html/Dolus_DDos/public> \
    Options Indexes FollowSymLinks \
    AllowOverride All \
    Require all granted \
</Directory> \
EOL

#Append the above text contained in file o to 000-default.conf
sed -i '/DocumentRoot/r o' 000-default.conf

sudo rm o

#Change PHP configuration files

cd etc/php/7.0/apache2/

sudo sed -i "s/.*php_pdo_mysql.*/extension=php_pdo_mysql.dll/" php.ini

cd /var/www/public_html
sudo service apache2 restart
cd /var/www/public_html/Dolus_DDos
composer install
sudo chmod -R 755 /var/www/public_html/Dolus_DDos
sudo chmod -R 777 storage
sudo chmod -R 775 bootstrap/cache/
sudo chmod -R 775 .env
php artisan key:generate
sudo pip install sqlalchemy
sudo pip install pymysql frenetic pycurl

sudo vim app/Python/settings.py
sudo vim config/database.php
sudo vim .env

python app/Python/models.py
python app/Python/defaultDataInsertion.py
