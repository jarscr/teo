<VirtualHost *:80>
	ServerName local.dev
	DocumentRoot /var/www/html
	ErrorLog /tmp/error.log
<Directory "/var/www/html">
Options +Includes
Options +FollowSymLinks -Indexes
AllowOverride All

</Directory>
</VirtualHost>