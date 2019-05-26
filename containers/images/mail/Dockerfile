FROM ruby:2.6

RUN gem install mailcatcher

# smtp (25) and ip (8080) ports
EXPOSE 25 8080

CMD ["mailcatcher", "--smtp-ip=0.0.0.0", "--smtp-port=25", "--http-ip=0.0.0.0", "--http-port=8080", "-f"]
