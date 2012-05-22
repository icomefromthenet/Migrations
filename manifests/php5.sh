#!/bin/bash

# Update aptitude.
apt-get -q update

# Install PHP with necessary modules
apt-get -q -y install php5  php5-suhosin 
apt-get -q -y install php5-cli
apt-get -q -y install libapache2-mod-php5

# which creates a symbolic link /etc/apache2/mods-enabled/php5 pointing to /etc/apache2/mods-availble/php5 . 
a2enmod php5

# restart apache
/etc/init.d/apache2 restart

# Install Extras
apt-get -q -y install php5-curl php-pear php5-xdebug curl php5-mcrypt php5-sqlite
#php5-gd php5-gmp php5-imap php5-ldap php5-mhash php5-ming php5-odbc php5-pspell php5-snmp php5-sybase php5-tidy libwww-perl imagemagick

exit 0;