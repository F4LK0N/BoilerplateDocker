#!/bin/bash

option="---"
while [ "$option" != "" ] && [ "$option" != "0" ]
do

    clear
    echo "$ADM_DIVIDER"
    echo "$ADM_LOGO"
    echo "$ADM_DIVIDER"
    echo "### NETWORK ###"
    echo "$ADM_DIVIDER"
    
    if [ "$option" == "---" ]; then

        echo " 0 = [BACK]"
        echo " 1 = Commands"
        echo " 2 = Info (Hostname, IP and MAC)"
        echo " 3 = Interfaces List"
        echo " 4 = Hosts File"
        echo " 5 = Ports Binded"
        echo " 6 = Total Traffic"
        
        echo " 5 = [REALTIME] Traffic (iftop)"
        echo "$ADM_DIVIDER"

        echo -n "Enter option: "
        read -r -s -n 1 option

    else

        if [ "$option" == "1" ]; then
            echo "- hostname"
            echo "- hostname -i"
            echo "- cat /sys/class/net/eth*/address"
            echo "- ls --color=auto -lAs /sys/class/net/eth*"
            echo "- cat /etc/hosts"
            echo "- lsof -i -n -P -R"
            echo "- cat /proc/net/dev | grep 'Inter-\|face\|eth'"
            


            
            echo "- iftop -n -N -B -P"
        fi

        if [ "$option" == "2" ]; then
            hostname=$( hostname )

            ip_addresses=$( hostname -i )
            ip_addresses="- ${ip_addresses//' '/$'\n- '}"

            mac_addresses=$( cat /sys/class/net/eth*/address )
            mac_addresses="- ${mac_addresses//$'\n'/$'\n- '}"

            echo "Hostname      : $hostname"
            echo ""
            echo "IP Addresses  :"
            echo "$ip_addresses"
            echo ""
            echo "MAC Addresses :"
            echo "$mac_addresses"
        fi

        if [ "$option" == "3" ]; then
            list=$( ls --color=auto -lAs /sys/class/net/eth* )
            echo "$list"
        fi

        if [ "$option" == "4" ]; then
            list=$( cat /etc/hosts )
            echo "$list"
        fi

        if [ "$option" == "5" ]; then
            #apt-get install lsof (462 kB)
            #lsof -nP -iTCP -sTCP:LISTEN
            #lsof -i -n -P -R

            #apt-get install net-tools (1015 kB)
            #netstat -plna
            #netstat -plnatu
            
            list=$( netstat -plna )
            echo "$list"
        fi

        if [ "$option" == "6" ]; then
            list=$( cat /proc/net/dev | grep 'Inter-\|face\|eth' )
            echo "$list"
        fi

        if [ "$option" == "7" ]; then
            list=$( cat /proc/net/dev | grep 'Inter-\|face\|eth' )
            echo "$list"
        fi

        if [ "$option" == "90" ]; then
            option=""
            while [ "$option" == "" ]
            do 
                list=$( ps -A )
                clear
                echo "$list"
                read -r -s -n 1 -t 0.25 option
            done
            option="DONTWAIT"
        fi


        if [ "$option" != "DONTWAIT" ]; then
            echo "$ADM_DIVIDER"
            echo -n "Enter to continue..."
            read -r -s -n 1 option
        fi
        option="---"

    fi

done
