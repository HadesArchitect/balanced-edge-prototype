FROM ubuntu:14.04
MAINTAINER Aleksandr Volochnev <a.volochnev@gmail.com>

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && apt-get install -y nano apache2 php5

RUN rm -v /etc/apache2/sites-enabled/*
ADD app.conf /etc/apache2/sites-enabled/
ADD index.php /var/www/app/
RUN sed -i "s/Listen 80/Listen 8090/" /etc/apache2/ports.conf

CMD /usr/sbin/apache2ctl -D FOREGROUND
