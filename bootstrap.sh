#!/usr/bin/env bash

apt-get update
apt-get install -y wget git linux-headers-generic build-essential dkms
wget -qO- https://get.docker.com/ | sh

sudo usermod -aG docker vagrant

curl -L https://github.com/docker/compose/releases/download/1.2.0/docker-compose-`uname -s`-`uname -m` > /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose

docker pull progrium/consul
docker pull gliderlabs/registrator
