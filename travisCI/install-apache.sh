#!/bin/bash
############################################################
# Let us Setup Apache2 for Ubuntu 16.04 with Php 7 and MySQL
############################################################
# Install Apache2
sudo apt-get update
sudo apt-get install -y --force-yes apache2
# Add PHP 7.3 Repository
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt-get install -y --force-yes php7.3 php7.3-common php7.3-mysql php7.3-xml php7.3-xmlrpc php7.3-curl php7.3-gd php7.3-imagick php7.3-cli php7.3-dev php7.3-imap php7.3-mbstring php7.3-opcache php7.3-soap php7.3-zip php7.3-intl -y
# Copy our virtual host template to sites-enabled overwriting the default site conf
sudo cp travisCI/defaultsite.tpl /etc/apache2/sites-available/000-default.conf
# Copy basic testing files into /var/www
sudo cp -R App /var/www/html/App
sudo cp -R Core /var/www/html/Core
sudo cp -R public /var/www/html/public
sudo cp .htaccess /var/www/html/.htaccess
# Enable mod rewrite module
sudo a2enmod rewrite
# Set ServerName Globally
sudo cp travisCI/servername.tpl /etc/apache2/conf-available/servername.conf
# Add testing of Apache Bad Bot Blocker

sudo wget https://raw.githubusercontent.com/mitchellkrogza/apache-ultimate-bad-bot-blocker/master/custom.d/globalblacklist.conf
sudo wget https://raw.githubusercontent.com/mitchellkrogza/apache-ultimate-bad-bot-blocker/master/custom.d/whitelist-ips.conf
sudo wget https://raw.githubusercontent.com/mitchellkrogza/apache-ultimate-bad-bot-blocker/master/custom.d/whitelist-domains.conf
sudo a2enconf servername
# Restart apache
sudo service apache2 restart
# Restart PHP
sudo service php7.3-fpm restart