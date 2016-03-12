FROM ubuntu:latest

RUN echo "America/Mexico_City" > /etc/timezone && dpkg-reconfigure --frontend noninteractive tzdata

COPY ./config/messaging-cron /etc/cron.d/messaging-cron
RUN chmod 0644 /etc/cron.d/messaging-cron

RUN touch /var/log/cron.log
CMD cron && tail -f /var/log/cron.log
