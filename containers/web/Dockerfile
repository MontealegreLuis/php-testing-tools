FROM php:5.6-apache

COPY ./config/entrypoint.sh /entrypoint.sh
COPY ./templates/ewallet.conf /etc/apache2/sites-available/
COPY ./config/php.ini /usr/local/etc/php/

# Install modules
RUN apt-get update && apt-get install -y \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install bcmath

# Grant rewrite permissions to our vhost
RUN sed -i '0,/all denied/{s/all denied/all granted/}'  /etc/apache2/apache2.conf
RUN a2enmod rewrite
RUN a2ensite ewallet

ENTRYPOINT ["/entrypoint.sh"]

EXPOSE 80
CMD ["apache2-foreground"]
