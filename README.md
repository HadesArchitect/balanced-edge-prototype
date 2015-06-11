# Prototype of load balanced edge

## Docker single-host mode

*To make some experiments or examine:*

1. Install https://www.docker.com/ and http://docs.docker.com/compose/
2. Clone the repository: *git clone git@github.com:HadesArchitect/balanced-edge-prototype.git edge*
3. Swith to prototype directory: *cd edge*
4. Run docker-compose: *docker-compose up* (use option -d to run in detached mode)
5. Please wait, initial launch could take some time
6. To scale processing layer use: *docker-compose scale app=N* (N stands for integer) 
7. To scale caching layer use: *docker-compose scale cache=N*
8. Check actual situation with: *docker-compose ps*
9. get your docker IP with:  *ifconfig docker0 | grep inet.addr*
10. Visit balancers at http://DOCKER_IP or http://DOCKER_IP:81
11. Visit another http-based services (app & cache) with http://DOCKER_IP:PORT_FROM_POINT_8

*To play with automatic service failover*

1. Add some instances of a tested layer (app or cache).
2. Drop previous instances of the layer (keep at least one).
3. Check the system - it's still works.

(Notice that Entry Points could be only switchovered in that scheme)

*Please, before any next launches of the prototype don't forget to:*

1. *docker-compose stop*
2. *docker-compose rm*
3. *docker-compose build*

## Vagrant double-host mode

1. Install https://www.vagrantup.com/ and https://www.virtualbox.org/
2. Run *vagrant plugin install vagrant-hostmanager* 
3. Clone the repository: *git clone git@github.com:HadesArchitect/balanced-edge-prototype.git edge*
4. Swith to prototype directory: *cd edge*
5. *vagrant up host-a host-b*
6. *vagrant hostmanager* to set up /etc/hosts

To start consul claster:

[todo: start services automatically]

1. *vagrant ssh host-a*
2. run cluster leader *docker run -h $HOSTNAME -p 10.0.0.10:8300:8300  -p 10.0.0.10:8301:8301  -p 10.0.0.10:8301:8301/udp -p 10.0.0.10:8302:8302 -p 10.0.0.10:8302:8302/udp -p 10.0.0.10:8400:8400 -p 10.0.0.10:8500:8500 -p 172.17.42.1:53:53 -p 172.17.42.1:53:53/udp -d progrium/consul -server -advertise 10.0.0.10 -bootstrap-expect 2*
3. exit
4. *vagrant ssh host-b*
5. run cluster member *docker run -h $HOSTNAME -p 10.0.0.11:8300:8300  -p 10.0.0.11:8301:8301  -p 10.0.0.11:8301:8301/udp -p 10.0.0.11:8302:8302 -p 10.0.0.11:8302:8302/udp -p 10.0.0.11:8400:8400 -p 10.0.0.11:8500:8500 -p 172.17.42.1:53:53 -p 172.17.42.1:53:53/udp -d progrium/consul -server -advertise 10.0.0.11 -join 10.0.0.10*
6. *exit*

If everything is done right, consul cluster is ready to register services. It could be checked with:

1. *docker ps* to get container ID
2. *docker logs CONTAINER_ID*

Typical output looks like:

```
    2015/06/11 13:34:22 [ERR] agent: failed to sync remote state: No cluster leader
    2015/06/11 13:34:38 [INFO] serf: EventMemberJoin: host-b 10.0.0.11
    2015/06/11 13:34:38 [INFO] consul: adding server host-b (Addr: 10.0.0.11:8300) (DC: dc1)
    2015/06/11 13:34:38 [INFO] consul: Attempting bootstrap with nodes: [10.0.0.10:8300 10.0.0.11:8300]
    2015/06/11 13:34:39 [WARN] raft: Heartbeat timeout reached, starting election
    2015/06/11 13:34:39 [INFO] raft: Node at 10.0.0.10:8300 [Candidate] entering Candidate state
    2015/06/11 13:34:39 [WARN] raft: Remote peer 10.0.0.11:8300 does not have local node 10.0.0.10:8300 as a peer
    2015/06/11 13:34:39 [INFO] raft: Election won. Tally: 2
    2015/06/11 13:34:39 [INFO] raft: Node at 10.0.0.10:8300 [Leader] entering Leader state
    2015/06/11 13:34:39 [INFO] consul: cluster leadership acquired
    2015/06/11 13:34:39 [INFO] consul: New leader elected: host-a
    2015/06/11 13:34:39 [INFO] raft: pipelining replication to peer 10.0.0.11:8300
    2015/06/11 13:34:39 [INFO] consul: member 'host-a' joined, marking health alive
    2015/06/11 13:34:39 [INFO] consul: member 'host-b' joined, marking health alive
    2015/06/11 13:34:39 [INFO] agent: Synced service 'consul'
```
