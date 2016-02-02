#!/bin/sh

username=""
password=""

hostname=`echo $1 | sed -E -e "s|^http[s]?://||"`
count=$2
# basic認証をチェック
if expr "${hostname}" : ".*hogehoge.*" ; then
username="hoge:"
password="huga@"
echo "match gokakunin"
fi

webkit2png -W 1280 -F -o ${count} -D ${HOME}/Desktop -W 1280 http://${username}${password}${hostname}
