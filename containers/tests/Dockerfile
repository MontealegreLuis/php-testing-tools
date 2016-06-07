FROM php:7.0-cli

COPY ./config/php.ini /usr/local/etc/php/

# Install modules
RUN apt-get update && apt-get install -y \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install bcmath

# Install PhantomJS
RUN apt-get install -y libfreetype6 libfontconfig bzip2
RUN curl -O -J -L https://bitbucket.org/ariya/phantomjs/downloads/phantomjs-1.9.8-linux-x86_64.tar.bz2
RUN tar xjf phantomjs-1.9.8-linux-x86_64.tar.bz2
RUN rm phantomjs-1.9.8-linux-x86_64.tar.bz2
RUN ln -s /phantomjs-1.9.8-linux-x86_64/bin/phantomjs /usr/bin/phantomjs

EXPOSE 4444
