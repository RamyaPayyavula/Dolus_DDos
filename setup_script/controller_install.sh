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

#Check to see if the script is run as root/sudo. If not, warn the user and exit.
if [[ $EUID -ne 0 ]] ; then
    echo -e "${RED}This script needs to be run as root. Please run this script again as root. Exiting.${NC}"
    exit
fi

#Update the system's available list of packages and their versions.
echo
echo "Updating the system's available list of packages..."
sudo apt-get -y update > /tmp/update_err.log 2>&1

#Check to see if the package lists were updated properly.
if [[ $? != 0 ]] ; then
    echo -e "\t${RED}The package lists were not installed properly. The error was saved to /tmp/update_err.log. Exiting.${NC}"
    exit
else
    #Remove the apache output log because it's not necessary.
    echo -e "\t${GREEN}Packages updated properly!${NC}"
    rm -f /tmp/update_err.log
fi

#Install the packages required for a LAMP stack, Python, Opam, and Mininet.
echo "Installing LAMP stack, Python, Opam, and Mininet..."
sudo apt-get -y install apache2 python-setuptools python-dev build-essential libcurl4-openssl-dev opam curl apt dpkg mininet m4 zlib1g-dev python-pip libmysqlclient-dev php libapache2-mod-php libssl-dev > /tmp/sw_install_err.log 2>&1

#Check to see if the package lists were updated properly.
if [[ $? != 0 ]] ; then
    echo -e "\t${RED}The software was not installed properly. The error was saved to /tmp/sw_install_err.log. Exiting.${NC}"
    exit
else
    #Remove the apache output log because it's not necessary.
    echo -e "\t${GREEN}The required software was installed properly!${NC}"
    rm -f /tmp/sw_install_err.log
fi

function installStatus() {
    #Run command passed to installStatus()
    "$@" > /tmp/install_err.log 2>&1 #Using this method instead of $* because $* will fail if any command arguments have spaces in them.
    ret=$?
    #Evaluate return value.
    if [[ $ret != 0 ]] ; then
        #Command did not execute properly.
        echo -e "\t${RED}The command "@" did not execute properly. The error was saved to /tmp/install_err.log.${NC}"
    else
        #Command executed properly. Remove the error log file because it is no longer necessary.
        rm -f /tmp/install_err.log
    fi
    echo $ret
}

#Configure Opam

RET_ARR=()

echo "Configuring Opam..."
RET_ARR+=($(installStatus sudo opam init -y))
RET_ARR+=($(installStatus sudo opam switch 4.06.0))
RET_ARR+=($(installStatus sudo opam switch))
installStatus eval `opam config env`
installStatus sudo echo 'eval `opam config env`' >> ~/.profile

for i in "${RET_ARR[@]}"; do
    if [[ $i != 0 ]]; then
        echo -e "\t${RED}Opam was not configured properly. Error was saved to /tmp/install_err.log. Exiting.${NC}"
        exit;
    fi
done
echo -e "\t${GREEN}Opam was configured properly.${NC}"

R_ARR=()

#Install frenetic
echo "Installing frenetic... (this might take a while)"

#Clear error file if exists.
> /tmp/frenetic_err.log

sudo opam pin add frenetic -y https://github.com/frenetic-lang/frenetic.git >> /tmp/frenetic_err.log 2>&1

#Save return values of commands run.
R_ARR+=($?)

pip install pycurl frenetic mysql >> /tmp/frenetic_err.log 2>&1

R_ARR+=($?)

sudo opam install frenetic >> /tmp/frenetic_err.log 2>&1

R_ARR+=($?)

# `pip install asdfasdf || \
#  opam install`

for i in "${R_ARR[@]}"; do
    if [[ $i != 0 ]]; then
        echo -e "\t${RED}Frenetic installation failed. Error was saved to /tmp/frenetic_err.log. Exiting.${NC}"
        exit
    fi
done
echo -e "\t${GREEN}Frenetic installation successful!${NC}"
rm -f /tmp/frenetic_err.log

#Create MySQL database

sudo apt-get -y install debconf-utils

sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password password'
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password password'
sudo apt-get -y install mysql-server

sudo apt-get -y install mysql-server apache2 php libapache2-mod-php php-mysql php-curl
cd /etc/mysql/mysql.conf.d 
sudo sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/g" mysqld.cnf

#Configure MySQL database
mysql -u root -ppassword << DB_SCRIPT
CREATE DATABASE lab2db;
CREATE USER 'monty'@'%' IDENTIFIED BY 'some_pass'; 
GRANT ALL PRIVILEGES ON *.* TO 'monty'@'%';
DB_SCRIPT

echo "Configuring MySQL"

sudo ufw allow 3306

sudo service mysql restart

sudo apt-get -y install dnsutils

publicIpAddr=$(dig +short myip.opendns.com @resolver1.opendns.com)

echo "The configuration for the controller has been completed!"
echo "To log in to the database on any of the switches, use:"
echo "${LIGHTBLUE}mysql -u monty -h $publicIpAddr${NC} -p"
echo "and enter the password ${LIGHTGREEN}some_pass${NC}"