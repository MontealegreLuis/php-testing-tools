FROM php:5.6-apache

COPY ./config/entrypoint.sh /entrypoint.sh
COPY ./config/ewallet.conf /etc/apache2/sites-available/
COPY ./templates/.env /usr/src/myapp/

RUN sed -i '0,/all denied/{s/all denied/all granted/}'  /etc/apache2/apache2.conf

RUN a2ensite ewallet
RUN a2enmod rewrite

ENTRYPOINT ["/entrypoint.sh"]

EXPOSE 80
CMD ["apache2-foreground"]
