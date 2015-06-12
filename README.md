# Prototype of load balanced edge

## Vagrant double-host mode

### Installation

1. Install https://www.vagrantup.com/ and https://www.virtualbox.org/
2. Run **vagrant plugin install vagrant-hostmanager** 
3. Clone the repository: **git clone git@github.com:HadesArchitect/balanced-edge-prototype.git edge**
4. Swith to prototype directory: **cd edge**
5. **vagrant up host-a host-b**
6. **vagrant hostmanager** to set up /etc/hosts (Could require password to set up host machine /etc/hosts)

To start consul claster:

[todo: start services automatically]

1. **vagrant ssh host-a**
2. run cluster leader **docker run -h $HOSTNAME -p 10.0.0.10:8300:8300  -p 10.0.0.10:8301:8301  -p 10.0.0.10:8301:8301/udp -p 10.0.0.10:8302:8302 -p 10.0.0.10:8302:8302/udp -p 10.0.0.10:8400:8400 -p 10.0.0.10:8500:8500 -p 172.17.42.1:53:53 -p 172.17.42.1:53:53/udp -d progrium/consul -server -advertise 10.0.0.10 -bootstrap-expect 2**
3. **exit**
4. **vagrant ssh host-b**
5. run cluster member **docker run -h $HOSTNAME -p 10.0.0.11:8300:8300  -p 10.0.0.11:8301:8301  -p 10.0.0.11:8301:8301/udp -p 10.0.0.11:8302:8302 -p 10.0.0.11:8302:8302/udp -p 10.0.0.11:8400:8400 -p 10.0.0.11:8500:8500 -p 172.17.42.1:53:53 -p 172.17.42.1:53:53/udp -d progrium/consul -server -advertise 10.0.0.11 -join 10.0.0.10**
6. **exit**

If everything is done right, consul cluster is ready to register services. It could be checked with:

1. **docker ps** to get container ID
2. **docker logs CONTAINER_ID**

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

Also you could check a state of the consul cluster through web-interface: http://host-a:8500/

Normally we will register services in consul through consul agent, but in this case it's much easier to use register and keep some time.

Run **docker run -d -v /var/run/docker.sock:/tmp/docker.sock -h $HOSTNAME gliderlabs/registrator consul://10.0.0.10:8500** on host A and **docker run -d -v /var/run/docker.sock:/tmp/docker.sock -h $HOSTNAME gliderlabs/registrator consul://10.0.0.11:8500** on host B.

Now auxillary services are ready and we could start primary ones. Let's start with backends - processors. Run **docker run -d -p 8090:8090 hadesarchitect/web-app:devel** on every node. After that you could see new services in consul web-interface - http://host-a:8500/ and visit apps personally: http://host-b:8090/.

Next step is cache layer. Run **docker run -d -p 8089:8089 hadesarchitect/cache:devel** on every node. Take a look at a running service in consul web-interface and visit http://host-b:8089/. 

Finally, let's start balancers. Run:

1. host-a: **docker run -d -p 80:80 -e "SERVICE_TAGS=main" hadesarchitect/balancer:devel**
2. host-b: **docker run -d -p 80:80 -e "SERVICE_TAGS=backup" hadesarchitect/balancer:devel**

As promised, we could see a new service in conul registry and visit a http://host-a or http://host-b.
