#!/bin/bash

# Update aptitude.
apt-get -q update

# Install MySQL.
export DEBIAN_FRONTEND=noninteractive
apt-get -q -y install mysql-server
apt-get -q -y install php5-mysql 
apt-get -q -y install mysql-client

# set new root password
mysqladmin -u root password vagrant

mysql -h localhost -u root -pvagrant <<< 'CREATE SCHEMA sakila;'

exit 0;