Vagrant.configure(2) do |config|

  config.vm.box = "ubuntu/trusty64"

  config.hostmanager.enabled = true
  config.hostmanager.manage_host = true

  config.vm.define "host-a" do |node|
      node.vm.hostname = "host-a"
      node.vm.network "private_network", ip: "10.0.0.10"
  end

  config.vm.define "host-b" do |node|
      node.vm.hostname = "host-b"      
      node.vm.network "private_network", ip: "10.0.0.11"
  end

  config.vm.define "host-c" do |node|
      node.vm.hostname = "host-c"
      node.vm.network "private_network", ip: "10.0.0.12"
  end

  # config.vm.network "public_network", bridge: "wlan0"

  # config.vm.synced_folder "../data", "/vagrant_data"

  config.vm.provision "fix-no-tty", type: "shell" do |s|
      s.privileged = false
      s.inline = "sudo sed -i '/tty/!s/mesg n/tty -s \\&\\& mesg n/' /root/.profile"
  end

  config.vm.provision :shell, path: "bootstrap.sh"
end
