#!/bin/sh

ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"

brew update
brew install imagemagick
brew install webkit2png
# brew install nodebrew

# nodebrew install-binary stable
# nodebrew use v0.12.0
# npm -g install casperjs
# wget https://bitbucket.org/ariya/phantomjs/downloads/phantomjs-1.9.7-macosx.zip
