# Prototype of load balanced edge

## Docker single-host mode

*To make some experiments or examine:*

1. Install https://www.docker.com/ and http://docs.docker.com/compose/
2. Clone the repository: git clone git@github.com:HadesArchitect/balanced-edge-prototype.git edge
3. Swith to prototype directory: cd edge
4. Run docker-compose: docker-compose up (use option -d to run in detached mode)
5. Please wait, initial launch could take some time
6. To scale processing layer use: docker-compose scale app=N 
7. To scale caching layer use: docker-compose scale cache=N
8. Check actual situation with: docker-compose ps
9. get your docker IP with:  ifconfig docker0 | grep inet.addr
10. Visit balancers at http://DOCKER_IP or http://DOCKER_IP:81
11. Visit another http-based services (app & cache) with http://DOCKER_IP:PORT_FROM_POINT_8

*To play with automatic service failover*

1. Add some instances of a tested layer (app or cache).
2. Drop previous instances of the layer (keep at least one).
3. Check the system - it's still works.

(Notice that Entry Points could be only switchovered in that scheme)

*Please, before any next launches of the prototype don't forget to:*

1. docker-compose stop
2. docker-compose rm
3. docker-compose build

## Vagrant double-host mode

1. Install https://www.vagrantup.com/ and https://www.virtualbox.org/
2. Run *vagrant plugin install vagrant-hostmanager* 
3. Clone the repository: git clone git@github.com:HadesArchitect/balanced-edge-prototype.git edge
4. Swith to prototype directory: cd edge
5. vagrant up host-a host-b
6. vagrant hostmanager


