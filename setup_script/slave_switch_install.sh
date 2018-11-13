#!/bin/bash

#Written by Andrew Krall, 11-08-2018

#This script will perform the configuration of the slave switch for the DDoS Lab 2.

#Color declarations
RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
LIGHTBLUE='\033[1;34m'
LIGHTGREEN='\033[1;32m'
NC='\033[0m' # No Color

#Ignore the weird spacing. I promise it looks good when it's echoed out to the screen.
echo -e ${LIGHTBLUE}"##################################################################"
echo "# DDoS Lab 2 Slave Switch Installation and Configuration Script  #"
echo "#                                                                #"
echo -e "# ${LIGHTGREEN}Syntax: sudo ./root_switch_install.sh controller_ip${LIGHTBLUE}            #"
echo "#                                                                #"
echo "# This script will take in the IP address of the controller as   #"
echo "# an argument. It will install all of the required software      #"
echo "# packages for the root switch, and ensure that it               #"
echo "# is configured properly.                                        #"
echo -e "##################################################################"${NC}
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

#Update the package lists on the slave switch.
echo -e "${BLUE}Updating the package lists on the slave switch.${NC}"
sudo apt-get update

#Check to see if the package lists were updated properly.
if [[ $? != 0 ]] ; then
    echo -e "${RED}The package lists were not installed properly. Exiting.${NC}"
    exit
else
    echo -e "${GREEN}Packages updated properly!${NC}"
fi

#Set Wireshark to not prompt user to choose if a non-root user should capture packets or not during installation.
echo -e "\n${BLUE}Configuring Wireshark preinstallation...${NC}"
sudo DEBIAN_FRONTEND=noninteractive apt-get -y install tshark
echo "wireshark-common wireshark-common/install-setuid boolean true" | sudo debconf-set-selections
sudo DEBIAN_FRONTEND=noninteractive dpkg-reconfigure wireshark-common || checkErr "Wireshark preinstallation configuration"

#Install Tshark to capture the OpenFlow packets.
echo -e "${BLUE}\nInstalling Tshark to capture the OpenFlow packets.${NC}"
sudo apt-get install -y openvswitch-switch tshark

#Check to see if Tshark was installed properly.
if [[ $? != 0 ]] ; then
    echo -e "${RED}Tshark was not installed properly. Exiting.${NC}"
    exit
else
    echo -e "${GREEN}Tshark was installed properly!${NC}"
fi

function checkErr() {
    echo -e "${RED}$1 failed. Exiting.${NC}" >&2; exit;
}

#Configure the network bridge on the slave switch.
echo -e "${BLUE}\nConfiguring the network bridge on the slave switch.${NC}"
#Create the bridge b0.
sudo ovs-vsctl add-br br0 || checkErr "Networking configuration"
#Activate the network interfaces connected to the slave switch, and add the network interface ports to b0.
sudo ifconfig eth1 0 || checkErr "Networking configuration"
sudo ifconfig eth2 0 || checkErr "Networking configuration"
sudo ifconfig eth3 0 || checkErr "Networking configuration"
sudo ifconfig eth4 0 || checkErr "Networking configuration"
sudo ifconfig eth5 0 || checkErr "Networking configuration"
sudo ifconfig eth6 0 || checkErr "Networking configuration"
sudo ovs-vsctl add-port br0 eth1 || checkErr "Networking configuration"
sudo ovs-vsctl add-port br0 eth2 || checkErr "Networking configuration"
sudo ovs-vsctl add-port br0 eth3 || checkErr "Networking configuration"
sudo ovs-vsctl add-port br0 eth4 || checkErr "Networking configuration"
sudo ovs-vsctl add-port br0 eth5 || checkErr "Networking configuration"
sudo ovs-vsctl add-port br0 eth6 || checkErr "Networking configuration"
#Configure the controller on bridge b0 for the slave switch
sudo ovs-vsctl set-controller br0 tcp:$ipAddress:6633 || checkErr "Networking configuration"

echo -e "\n${GREEN}Network bridge configuration successful!${NC}"

echo -e "${GREEN}Configuration of the slave switch has been completed. Please go back to the controller"
echo "and take note of the DPID number displayed on the switch. It should be a 14-digit"
echo -e "number that will be used to identify the slave switch later.${NC}"