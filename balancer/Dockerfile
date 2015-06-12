FROM nginx:1.7
MAINTAINER Aleksandr Volochnev <a.volochnev@gmail.com>

RUN DEBIAN_FRONTEND=noninteractive \
    apt-get update -qq && \
    apt-get -y install curl runit

RUN rm -rf /var/lib/apt/lists/*

ENV CT_URL https://github.com/hashicorp/consul-template/releases/download/v0.10.0/consul-template_0.10.0_linux_amd64.tar.gz
RUN curl -L $CT_URL | tar -C /usr/local/bin --strip-components 1 -zxf -

RUN rm -v /etc/nginx/conf.d/*

ADD nginx.ctmpl /etc/consul-templates/nginx.ctmpl

ADD nginx.service /etc/service/nginx/run
ADD consul-template.service /etc/service/consul-template/run
RUN chmod +x /etc/service/nginx/run /etc/service/consul-template/run

CMD ["/usr/bin/runsvdir", "/etc/service"]
