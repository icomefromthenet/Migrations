#!/bin/bash

# Upgrades PEAR.
pear upgrade PEAR

# Phing.
pear channel-discover pear.phing.info
pear install phing/phing

# Composer.
cd /usr/bin
wget http://getcomposer.org/composer.phar
chmod a+x composer.phar

# install phar
cd /usr/bin
wget http://pear2.php.net/pyrus.phar
chmod 755 /usr/bin/pyrus.phar


echo ';used to enable phar ext' >> /etc/php5/cli/php.ini
echo 'suhosin.executor.include.whitelist="phar"' >> /etc/php5/cli/php.ini


# Install Pear Package Manager

pear config-set preferred_state alpha
pear install XML_Serializer
pear install PEAR_PackageFileManager2


# install prium

pear channel-discover pear.pirum-project.org
pear install pirum/Pirum

# Discover my own pear channel

pear channel-discover icomefromthenet.github.com/pear

exit 0;