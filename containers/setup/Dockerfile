FROM php:7.0-cli

COPY ./config/php.ini /usr/local/etc/php/
COPY ./config/auth.json $HOME/.composer/

# Install modules
RUN apt-get update && apt-get install -y \
    && apt-get install -y zlib1g zlib1g-dev git-core \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer --version
