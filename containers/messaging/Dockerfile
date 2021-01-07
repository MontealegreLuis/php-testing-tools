FROM php:7.4.12-cli

RUN echo "America/Mexico_City" > /etc/timezone && dpkg-reconfigure --frontend noninteractive tzdata

COPY ./config/php.ini /usr/local/etc/php/

# Install modules
RUN apt-get update \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install sockets

COPY ./config/messaging-cron /etc/cron.d/messaging-cron
RUN chmod 0644 /etc/cron.d/messaging-cron

RUN apt-get install -y msmtp && \
    echo "sendmail_path = /usr/bin/msmtp -C /root/.msmtprc -t" > /usr/local/etc/php/conf.d/sendmail.ini && \
    echo "host mail\nport 25\ntls_starttls off\ntls_certcheck off\n" > /root/.msmtprc

RUN apt-get install -y cron

# Run the command on container startup
CMD env >> /etc/environment && cron -f
