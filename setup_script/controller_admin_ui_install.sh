#!/bin/bash

#Written by Andrew Krall, 11-02-2018

#This script is used to install and configure AdminUI on the controller for the DDoS Lab 2.

#Color declarations
RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
LIGHTBLUE='\033[1;34m'
LIGHTGREEN='\033[1;32m'
NC='\033[0m' # No Color

#Ignore the weird spacing. I promise it looks good when it's echoed out to the screen.
echo -e ${LIGHTBLUE}"################################################################"
echo "# DDoS Lab 2 Controller AdminUI Configuration Script           #"
echo "#                                                              #"
echo -e "# ${LIGHTGREEN}Syntax: sudo ./controller_admin_ui_install.sh  $rootDPID $slaveDPID ${LIGHTBLUE}            #"
echo "#                                                              #"
echo "# This script will install and configure the AdminUI software  #"
echo "# on the controller, and ensure that it is                     #"
echo "# configured properly. The required arguments are the          #"
echo "# root-switch and slave-switch DPID numbers.                   #"
echo -e "################################################################"${NC}
echo "" #Acts as a newline by outputting nothing.

function checkErr() {
    echo -e "${RED}$1 failed. Exiting.${NC}" >&2; exit;
}

#Check to see if the script is run as root/sudo. If not, warn the user and exit.
if [[ $EUID -ne 0 ]] ; then
    echo -e "${RED}This script needs to be run as root. Please run this script again as root. Exiting.${NC}"
    exit
fi

#Check to see if an argument has been supplied. If not, exit the script.
if [ -z "$2" ] ; then
    echo -e "${RED}Incorrect number of arguments supplied. Please supply the DPID numbers of the root switch and the slave switch as arguments. Exiting.${NC}"
    exit
fi

regex='^[0-9]+$'

if ! { [[ "$1" =~ $regex ]] && [[ "$2" =~ $regex ]]; } ; then
    echo -e "${RED}One or multiple of the DPID numbers you entered was not an integer. Please ensure both DPID numbers are integers when entering them as arguments. Exiting.${NC}"
    exit
fi

rootDPID=$1
slaveDPID=$2

#First, set the apache default site to point to /var/www/public_html and restart the apache2 server.
echo -e "${BLUE}Configuring the Apache server...${NC}"
cd /etc/apache2/sites-enabled
sudo sed -i s+var/www/html+var/www/public_html+g 000-default.conf
cd /var/www
sudo mv /var/www/html /var/www/public_html
sudo service apache2 restart || checkErr "Apache configuration"
echo -e "${GREEN}Apache configuration complete!"

echo -e "\n${BLUE}Installing Composer and cloning the Dolus_DDoS repository into the Apache root directory...${NC}"
cd /var/www/public_html
sudo rm index.html
sudo echo "<?php echo phpinfo(); ?>" > index.php
cd
sudo apt-get -y install composer || checkErr "The installation of composer"
cd /var/www/public_html
sudo git clone https://github.com/RamyaPayyavula/Dolus_DDos || checkErr "Attempting to clone the git repository"
cd Dolus_DDos
sudo chmod -R 777 /var/www/
sudo chmod -R 774 /var/www/public_html/Dolus_DDos
echo -e "${GREEN}Composer installation and repository cloning complete!${NC}"

echo -e "\n${BLUE}Installing and configuring PHP...${NC}"
sudo apt-get -y install php-mbstring php-xml || checkErr "PHP dependency installation"
composer update || checkErr "Composer update"
composer dump-autoload || checkErr "Composer dump-autoload"
sudo apt-get -y install php7.0-zip || checkErr "PHP installation"
composer global require "laravel/installer"
sudo a2enmod rewrite
cd /etc/apache2/sites-enabled
sudo sed -i "s+.*DocumentRoot.*+DocumentRoot /var/www/public_html/Dolus_DDos/public+g" 000-default.conf
#Create temporary file to put multiple lines into, then append those lines to 000-default.conf
sudo touch o && sudo chmod 666 o
#Add the following lines below the DocumentRoot line
cat >> o <<EOL

<Directory /var/www/public_html/Dolus_DDos/public> 
    Options Indexes FollowSymLinks 
    AllowOverride All 
    Require all granted 
</Directory> 
EOL

#Append the above text contained in file o to 000-default.conf
sed -i '/DocumentRoot/r o' 000-default.conf

sudo rm o

#Change PHP configuration files

cd etc/php/7.0/apache2/

sudo sed -i "s/.*php_pdo_mysql.*/extension=php_pdo_mysql.dll/" php.ini

cd /var/www/public_html
sudo service apache2 restart || checkErr "Apache configuration"
echo -e "${GREEN}PHP installation and configuration complete!"

echo -e "\n${BLUE}Setting up the AdminUI webpage...${NC}"
cd /var/www/public_html/Dolus_DDos
composer install || checkErr "Composer update"
sudo chmod -R 755 /var/www/public_html/Dolus_DDos
sudo chmod -R 777 storage
sudo chmod -R 775 bootstrap/cache/
sudo chmod -R 775 .env
php artisan key:generate || checkErr "PHP key generation"
sudo pip install sqlalchemy || checkErr "SQLAlchemy installation"
sudo pip install pymysql frenetic pycurl || checkErr "Python dependency installation"

python app/Python/models.py || checkErr "Running app/Python/models.py"

#Before running defaultDataInsertion.py, we'll need to change the DPID numbers in the file to match the ones input as arguments to this script.

#Change the DPID numbers for the first three instances of switchID to the root switch's DPID number.
awk -v rootDPID="$rootDPID" 'BEGIN {matches=0}
     matches < 3 && /.*switchID.*/ { sub(/switchID=.[0-9]+./,"switchID=\""rootDPID"\""); matches++ }
     { print $0 }' app/Python/defaultDataInsertion.py > app/Python/defaultDataInsertion.py.1

#Change the DPID numbers for the next six instances of switchID to the slave switch's DPID number.
awk -v slaveDPID="$slaveDPID" 'BEGIN {matches=0}
    matches >= 3 && matches < 9 && /.*switchID.*/ { sub(/switchID=.[0-9]+./,"switchID=\""slaveDPID"\"") }
    /.*switchID.*/ { matches++ }
    { print $0 }' app/Python/defaultDataInsertion.py.1 > app/Python/defaultDataInsertion.py

#Now, change the root switch's DPID number in the Switches objects array.
awk -v rootDPID="$rootDPID" ' /.*root-switch.*/ { sub(/switchID=.[0-9]+./,"switchID=\""rootDPID"\"")}
     { print $0 }' app/Python/defaultDataInsertion.py > app/Python/defaultDataInsertion.py.1

#Do the same for the slave switch's DPID number.
awk -v slaveDPID="$slaveDPID" ' /.*slave-switch.*/ { sub(/switchID=.[0-9]+./,"switchID=\""slaveDPID"\"")}
     { print $0 }' app/Python/defaultDataInsertion.py.1 > app/Python/defaultDataInsertion.py

rm -f app/Python/defaultDataInsertion.py.1

python app/Python/defaultDataInsertion.py || checkErr "Running app/Python/defaultDataInsertion.py"
echo -e "${GREEN}The AdminUI webpage has been configured properly!"

echo -e "\n${GREEN}The AdminUI installation has been completed!"
echo -e "You can now complete the rest of the lab!${NC}"