#!/bin/sh

username=""
password=""

hostname=`echo $1 | sed -e "s|^http://||"`
count=$2

if expr "${hostname}" : ".*tstwww.*" ; then
username="tstwww:"
paddword="Fg6jNcsp@"
echo "match tstwww"
fi
if expr "${hostname}" : ".*gokakunin.*" ; then
username="jfritp:"
paddword="jfrtest"
echo "match gokakunin"
fi

webkit2png -W 1280 -F -o ${count} -D ${HOME}/Desktop -W 1280 http://${user}${password}${hostname}
