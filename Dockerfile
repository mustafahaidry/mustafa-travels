FROM php:8.2-apache

RUN apt-get update && apt-get install -y curl libcurl4-openssl-dev \
    && docker-php-ext-install curl

COPY . /var/www/html/

EXPOSE 80

CMD ["apache2-foreground"]
