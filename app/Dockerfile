FROM phusion/baseimage:0.9.16
MAINTAINER Aleksandr Volochnev <a.volochnev@gmail.com>

RUN locale-gen en_US.UTF-8
ENV LANG       en_US.UTF-8
ENV LC_ALL     en_US.UTF-8
ENV HOME /root
ENV DEBIAN_FRONTEND="noninteractive"
ENV DEBIAN_FRONTEND noninteractive
ENV SERVICE_NAME app

CMD ["/sbin/my_init"]

RUN apt-get update
RUN apt-get install -y curl wget build-essential python-software-properties

RUN add-apt-repository -y ppa:nginx/stable

RUN wget -O - http://dl.hhvm.com/conf/hhvm.gpg.key | sudo apt-key add -
RUN echo deb http://dl.hhvm.com/ubuntu trusty main | sudo tee /etc/apt/sources.list.d/hhvm.list

RUN apt-get update

RUN apt-get install -y nginx hhvm

RUN rm -v /etc/nginx/sites-enabled/*
ADD app.conf /etc/nginx/sites-enabled/

RUN mkdir    /etc/service/nginx
ADD nginx.sh /etc/service/nginx/run
RUN chmod +x /etc/service/nginx/run

RUN ln -sf /dev/stdout /var/log/nginx/access.log
RUN ln -sf /dev/stderr /var/log/nginx/error.log

RUN mkdir /etc/service/hhvm
ADD hhvm.sh /etc/service/hhvm/run
RUN chmod +x /etc/service/hhvm/run

#ADD index.php /var/www/app/
#ADD cat.jpg /var/www/app/
