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

#Configure Opam

echo "Configuring Opam..."
sudo opam init -y
sudo opam switch 4.06.0
sudo opam switch
eval `opam config env`
sudo echo 'eval `opam config env`' >>.profile