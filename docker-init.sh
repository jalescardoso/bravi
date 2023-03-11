#!/bin/bash
sed -i "s/Listen 80/Listen ${PORT:-80}/g" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT:-80}/g" /etc/apache2/sites-enabled/*
sed -i "s/ServerTokens OS/ServerTokens Prod/g" /etc/apache2/conf-enabled/security.conf
sed -i "s/ServerSignature On/ServerSignature Off/g" /etc/apache2/conf-enabled/security.conf
echo -e "\nHeader unset X-Powered-By" >> /etc/apache2/conf-enabled/security.conf

sed -i "s/;pcre.backtrack_limit=100000/pcre.backtrack_limit=5000000/g" $PHP_INI_DIR/php.ini
sed -i "s/post_max_size = 8M/post_max_size = 30M/g" $PHP_INI_DIR/php.ini
sed -i "s/upload_max_filesize = 2M/upload_max_filesize = 30M/g" $PHP_INI_DIR/php.ini
sed -i "s/memory_limit = 128M/memory_limit = 1024M/g" $PHP_INI_DIR/php.ini

echo "<VirtualHost *:80>
    ServerAdmin suporte@ileva.com.br
    DocumentRoot /var/www/html
    <Directory /var/www/html/>
        Options SymLinksIfOwnerMatch
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog /var/log/apache2/error.log
    CustomLog /var/log/apache2/access.log combined
    <IfModule mod_dir.c>
        DirectoryIndex index.php index.pl index.cgi index.html index.xhtml index.htm
    </IfModule>
</VirtualHost>
" > /etc/apache2/sites-available/000-default.conf

sed -i "s/KeepAlive On/KeepAlive Off/g" /etc/apache2/apache2.conf
sed -i "s/MaxKeepAliveRequests 100/MaxKeepAliveRequests 60/g" /etc/apache2/apache2.conf
sed -i "s/MaxConnectionsPerChild   0/MaxConnectionsPerChild   300/g" /etc/apache2/mods-available/mpm_prefork.conf

timedatectl set-timezone America/Sao_Paulo
ln -sf /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime

apache2-foreground