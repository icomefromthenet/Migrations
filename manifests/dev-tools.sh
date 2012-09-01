#!/bin/bash

# Update aptitude.
apt-get -q update

apt-get -q -y install git-core

# SCM tools & utils.
apt-get -q -y install byobu
exit 0;