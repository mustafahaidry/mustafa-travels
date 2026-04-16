FROM php:8.2-apache

RUN apt-get update && apt-get install -y curl libcurl4-openssl-dev \
    && docker-php-ext-install curl

# Enable Apache modules
RUN a2enmod headers
RUN a2enmod rewrite

# Copy custom Apache config to hide server version
RUN echo "ServerTokens Prod" >> /etc/apache2/apache2.conf
RUN echo "ServerSignature Off" >> /etc/apache2/apache2.conf

# Security Headers
RUN echo "Header set X-Content-Type-Options \"nosniff\"" >> /etc/apache2/apache2.conf
RUN echo "Header set X-Frame-Options \"SAMEORIGIN\"" >> /etc/apache2/apache2.conf
RUN echo "Header set X-XSS-Protection \"1; mode=block\"" >> /etc/apache2/apache2.conf

COPY . /var/www/html/

EXPOSE 80

CMD ["apache2-foreground"]
