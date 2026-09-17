FROM php:8.3-apache
RUN docker-php-ext-install mysqli
COPY deployment/apache.conf /etc/apache2/conf-enabled/smart-restroom.conf
COPY deployment/php.ini /usr/local/etc/php/conf.d/production.ini
COPY . /var/www/html
RUN chmod +x /var/www/html/deployment/start.sh
EXPOSE 80
CMD ["/var/www/html/deployment/start.sh"]
