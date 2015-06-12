FROM ubuntu:14.04
MAINTAINER Aleksandr Volochnev <a.volochnev@gmail.com>

ENV DEBIAN_FRONTEND=noninteractive
ENV SERVICE_NAME cache

RUN apt-get update -qq
RUN apt-get -y install apt-transport-https curl runit
RUN curl https://repo.varnish-cache.org/GPG-key.txt | apt-key add -
RUN echo "deb https://repo.varnish-cache.org/ubuntu/ trusty varnish-4.0" >> /etc/apt/sources.list.d/varnish-cache.list
RUN apt-get update -qq
RUN apt-get -y install varnish

RUN rm -rf /var/lib/apt/lists/*

ENV CT_URL https://github.com/hashicorp/consul-template/releases/download/v0.10.0/consul-template_0.10.0_linux_amd64.tar.gz
RUN curl -L $CT_URL | tar -C /usr/local/bin --strip-components 1 -zxf -

ADD consul-template.service /etc/service/consul-template/run
RUN chmod +x /etc/service/consul-template/run

ADD varnish.ctmpl /etc/consul-templates/varnish.ctmpl
ADD varnish.service /etc/service/varnish/run
RUN chmod +x /etc/service/varnish/run

CMD ["/usr/bin/runsvdir", "/etc/service"]
