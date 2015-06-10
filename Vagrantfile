Vagrant.configure(2) do |config|

  config.vm.box = "ubuntu/trusty64"

  config.vm.define "host-a" do |web|
      config.vm.hostname = "host-a"
  end

  config.vm.define "host-b" do |db|
      config.vm.hostname = "host-b"      
  end

  config.vm.network "private_network", type: "dhcp"
  # config.vm.network "public_network", bridge: "wlan0"

  # config.vm.synced_folder "../data", "/vagrant_data"

  config.vm.provision "fix-no-tty", type: "shell" do |s|
      s.privileged = false
      s.inline = "sudo sed -i '/tty/!s/mesg n/tty -s \\&\\& mesg n/' /root/.profile"
  end

  config.vm.provision :shell, path: "bootstrap.sh"
end
