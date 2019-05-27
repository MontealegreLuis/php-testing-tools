FROM php:7.1-cli

COPY ./config/php.ini /usr/local/etc/php/

# Install modules
RUN apt-get update && apt-get install -y \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install bcmath

# Add the console `bin` folder to the PATH environment variable
ENV PATH /usr/src/myapp/ui/console/bin:$PATH
