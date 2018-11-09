#!/bin/bash

#Written by Andrew Krall, 11-02-2018

#This script is used to configure the controller for the DDoS Lab 2.

#Note: Give an option to continue the script at certain points if erroring out would be detrimental.

#Color declarations
RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
LIGHTBLUE='\033[1;34m'
LIGHTGREEN='\033[1;32m'
NC='\033[0m' # No Color

#Ignore the weird spacing. I promise it looks good when it's echoed out to the screen.
echo -e ${LIGHTBLUE}"################################################################"
echo "# DDoS Lab 2 Controller Installation and Configuration Script  #"
echo "#                                                              #"
echo -e "# ${LIGHTGREEN}Syntax: sudo ./controller_install.sh                   ${LIGHTBLUE}      #"
echo "#                                                              #"
echo "# This script will install all of the required software        #"
echo "# packages for the controller, and ensure that it is           #"
echo "# configured properly.                                         #"
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

#Update the system's available list of packages and their versions.
echo -e "${BLUE}Updating the system's available list of packages...${NC}"
sudo apt-get -y update || checkErr "Package list installation"

echo -e "${GREEN}Packages updated properly!${NC}"


#Install the packages required for a LAMP stack, Python, Opam, and Mininet.
echo -e "${BLUE}\nInstalling LAMP stack, Python, Opam, and Mininet...${NC}"
sudo apt-get -y install apache2 python-setuptools python-dev build-essential libcurl4-openssl-dev opam curl apt dpkg mininet m4 zlib1g-dev python-pip libmysqlclient-dev php libapache2-mod-php libssl-dev || checkErr "Package installation"
echo -e "${GREEN}The required software was installed properly!${NC}"

#Configure Opam

echo -e "${BLUE}Configuring Opam...${NC}"
sudo opam init -y || checkErr "Opam configuration"
sudo opam switch 4.06.0 || checkErr "Opam configuration"
sudo opam switch || checkErr "Opam configuration"
eval `opam config env` || checkErr "Opam configuration"
sudo echo 'eval `opam config env`' >> ~/.profile || checkErr "Opam configuration"

echo -e "${GREEN}Opam was configured properly.${NC}"

#Install frenetic
echo -e "${BLUE}Installing frenetic... (this might take a while)${NC}"

sudo opam pin add frenetic -y https://github.com/frenetic-lang/frenetic.git || checkErr "Frenetic installation"
pip install pycurl frenetic mysql|| checkErr "Frenetic installation"
sudo opam install frenetic || checkErr "Frenetic installation"

echo -e "${GREEN}Frenetic installation successful!${NC}"

#Create MySQL database

echo -e "${BLUE}Installing MySQL database...${NC}"

sudo apt-get -y install debconf-utils || checkErr "Debconf Utils Installation"

sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password password' || checkErr "Debconf configuration"
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password password' || checkErr "Debconf configuration"
sudo apt-get -y install mysql-server || checkErr "MySQL installation"

sudo apt-get -y install mysql-server apache2 php libapache2-mod-php php-mysql php-curl || checkErr "MySQL installation"

echo -e "${GREEN}MySQL installation complete!"

#Configure MySQL database
echo -e "${BLUE}Configuring MySQL database${NC}"

cd /etc/mysql/mysql.conf.d 
sudo sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/g" mysqld.cnf

mysql -u root -ppassword << DB_SCRIPT
CREATE DATABASE lab2db;
CREATE USER 'monty'@'%' IDENTIFIED BY 'some_pass'; 
GRANT ALL PRIVILEGES ON *.* TO 'monty'@'%';
DB_SCRIPT

sudo ufw allow 3306 || checkErr "MySQL configuration"

sudo service mysql restart || checkErr "MySQL configuration"

echo -e "${GREEN}MySQL configuration complete!"

echo -e "${BLUE}\nInstalling DNSUtils package to obtain the controller's public IP address...${NC}"

sudo apt-get -y install dnsutils || checkErr "DNSUtils package"

publicIpAddr=$(dig +short myip.opendns.com @resolver1.opendns.com)

if [ -z "$publicIpAddr" ] ; then
    echo "${RED}The controller public IP address could not be obtained. Exiting.${NC}"
    exit
fi

echo -e "${GREEN}The controller's public IP address was obtained successfully!${NC}"

echo "The configuration for the controller has been completed!"
echo "To log in to the database on any of the switches, use:"
echo "${LIGHTBLUE}mysql -u monty -h $publicIpAddr${NC} -p"
echo "and enter the password ${LIGHTGREEN}some_pass${NC}"