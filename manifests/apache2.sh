# Install Apache version 2 web server
apt-get -q -y install apache2 apache2-doc

# Set local host on apache
echo "ServerName localhost" | tee /etc/apache2/conf.d/fqdn

#restart apache
/etc/init.d/apache2 restart

exit 0;