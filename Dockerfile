FROM php:8.2-apache
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html
COPY ./php/src /var/www/html

RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html/assets/images

EXPOSE 80
