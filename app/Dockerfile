FROM ubuntu:14.04
MAINTAINER Aleksandr Volochnev <a.volochnev@gmail.com>

ENV DEBIAN_FRONTEND noninteractive
ENV SERVICE_NAME app

RUN apt-get install -y php5-cli php5-gd

ADD index.php /var/www/app/
ADD cat.jpg /var/www/app/

EXPOSE 8090

CMD php -S 0.0.0.0:8090 -t /var/www/app
