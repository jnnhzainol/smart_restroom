FROM php:8.3-apache

RUN docker-php-ext-install mysqli

COPY . /var/www/html

CMD ["sh", "-c", "port=${PORT:-8080}; sed -ri \"s/^Listen 80$/Listen ${port}/\" /etc/apache2/ports.conf; sed -ri \"s/:80>/:${port}>/\" /etc/apache2/sites-available/000-default.conf; apache2-foreground"]
