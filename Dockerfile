FROM php:8.2-apache

RUN apt-get update && apt-get install -y curl libcurl4-openssl-dev \
    && docker-php-ext-install curl

# Hide Apache version
RUN echo "ServerTokens Prod" >> /etc/apache2/apache2.conf
RUN echo "ServerSignature Off" >> /etc/apache2/apache2.conf

# Hide PHP version
RUN echo "expose_php = Off" >> /usr/local/etc/php/conf.d/php.ini

# Security Headers
RUN a2enmod headers
RUN echo "Header set X-Content-Type-Options \"nosniff\"" >> /etc/apache2/apache2.conf
RUN echo "Header set X-Frame-Options \"SAMEORIGIN\"" >> /etc/apache2/apache2.conf

COPY . /var/www/html/

EXPOSE 80

CMD ["apache2-foreground"]
