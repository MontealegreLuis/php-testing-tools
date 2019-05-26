FROM php:7.1-cli

RUN echo "America/Mexico_City" > /etc/timezone && dpkg-reconfigure --frontend noninteractive tzdata

COPY ./config/php.ini /usr/local/etc/php/

# Install modules
RUN apt-get update && apt-get install -y \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install bcmath

COPY ./config/messaging-cron /etc/cron.d/messaging-cron
RUN chmod 0644 /etc/cron.d/messaging-cron

RUN apt-get install -y ssmtp && \
    echo "sendmail_path = /usr/sbin/ssmtp -t" > /usr/local/etc/php/conf.d/sendmail.ini && \
    echo "mailhub=mail:25\nUseTLS=NO\nFromLineOverride=YES" > /etc/ssmtp/ssmtp.conf

RUN apt-get install -y cron

# Run the command on container startup
CMD env >> /etc/environment && cron -f
