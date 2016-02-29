FROM php:5.6-cli

COPY ./config/php.ini /usr/local/etc/php/

# Install modules
RUN apt-get update && apt-get install -y \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install bcmath

# Create alias to run the console application
ENV PATH /usr/src/myapp/applications/console/bin:$PATH
