#!/bin/sh
flag=`defaults read com.apple.finder AppleShowAllFiles`
if [ ${flag} = "true" ]; then
    echo "AppleShowAllFiles write false from "${flag}
    defaults write com.apple.finder AppleShowAllFiles false
    killall Finder
else
    echo "AppleShowAllFiles write true from "${flag}
    defaults write com.apple.finder AppleShowAllFiles true
    killall Finder
fi
