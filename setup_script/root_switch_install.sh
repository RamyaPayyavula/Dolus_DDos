#!/bin/bash

#Written by Andrew Krall, 11-08-2018

#This script will perform the configuration of the root switch for the DDoS Lab 2.

#Color declarations
RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
LIGHTBLUE='\033[1;34m'
LIGHTGREEN='\033[1;32m'
NC='\033[0m' # No Color

#Send the IP address of the controller as a parameter.

#Ignore the weird spacing. I promise it looks good when it's echoed out to the screen.
echo -e ${LIGHTBLUE}"#################################################################"
echo "# DDoS Lab 2 Root Switch Installation and Configuration Script  #"
echo "#                                                               #"
echo -e "# ${LIGHTGREEN}Syntax: sudo ./root_switch_install.sh controller_ip${LIGHTBLUE}           #"
echo "#                                                               #"
echo "# This script will take in the IP address of the controller as  #"
echo "# an argument. It will install all of the required software     #"
echo "# packages for the root switch, and ensure that it              #"
echo "# is configured properly.                                       #"
echo -e "#################################################################"${NC}
echo "" #Acts as a newline by outputting nothing.

#Check to see if the script is run as root/sudo. If not, warn the user and exit.
if [[ $EUID -ne 0 ]] ; then
    echo -e "${RED}This script needs to be run as root. Please run this script again as root. Exiting.${NC}"
    exit
fi

#Check to see if an argument has been supplied. If not, exit the script.
if [ -z "$1" ] ; then
    echo -e "${RED}No arguments supplied. Please supply the IP address of the controller as an argument. Exiting.${NC}"
    exit
else
    mysql_root_pw="$1"
fi

#This function tests to see if an IP address is valid or not.
#Taken from https://www.linuxjournal.com/content/validating-ip-address-bash-script
function valid_ip()
{
    local  ip=$1
    local  stat=1

    if [[ $ip =~ ^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$ ]]; then
        OIFS=$IFS
        IFS='.'
        ip=($ip)
        IFS=$OIFS
        [[ ${ip[0]} -le 255 && ${ip[1]} -le 255 \
            && ${ip[2]} -le 255 && ${ip[3]} -le 255 ]]
        stat=$?
    fi
    return $stat
}

#Check if IP address is passed as a parameter. 
if ! valid_ip $1 ; then
    echo -e "${RED}You entered an invalid IP address. Please enter a valid IP address as the first parameter. Exiting.${NC}"
    exit
else
    ipAddress="$1"
fi

#Update the package lists on the root switch.
echo "Updating the package lists on the root switch."
sudo apt-get update

#Install Tshark to capture the OpenFlow packets.
echo "Installing Tshark to capture the OpenFlow packets."
sudo apt-get install -y openvswitch-switch tshark

#Configure the network bridge on the root switch.
echo "Configuring the network bridge on the root switch."
#Create the bridge b0.
sudo ovs-vsctl add-br br0
#Activate the network interface connected to the root switch, and add the network interface ports to b0.
sudo ifconfig eth1 0 
sudo ifconfig eth2 0 
sudo ifconfig eth3 0 
sudo ovs-vsctl add-port br0 eth1 
sudo ovs-vsctl add-port br0 eth2 
sudo ovs-vsctl add-port br0 eth3
