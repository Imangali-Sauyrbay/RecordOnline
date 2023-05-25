## Setup your linux server
___
### Prequisits:
1. git-all
1. docker
1. docker-compose

### Domains
#### For main site
1. sub.domain.com
#### For websocket server
1. sub-ws.domain.com

PS: Self hosted node.js ws server was used, cuz php not have ws connection... yet.
___
<br />

### For debian like distros:

<br />

#### Git installation
```bash
sudo apt-get update && sudo apt-get install git-all
```

<br />

#### Docker installation

```bash
# remove older versions
sudo apt-get remove docker docker-engine docker.io containerd runc

# for https
sudo apt-get install ca-certificates curl gnupg

# Docker official gpg key
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/debian/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg

# Setup repo
echo \
  "deb [arch="$(dpkg --print-architecture)" signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/debian \
  "$(. /etc/os-release && echo "$VERSION_CODENAME")" stable" | \
  sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# Installation
sudo apt-get update

sudo apt-get install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

# Now you can test docker

sudo docker run hello-world
```

<br />

#### Docker Compose installation

```bash
sudo curl -L https://github.com/docker/compose/releases/download/v2.18.1/docker-compose-linux-x86_64 -o /usr/local/bin/docker-compose

chmod +x /usr/local/bin/docker-compose

docker-compose --version
```

<br />

## Configure server

### Change directory (*optional)

```bash
cd /var/www/html

# Not recomended if you already have server on this folder!
# clear current folder
rm -rf ./*
```

<br />

### Clone git repo

```bash
git clone https://github.com/Imangali-Sauyrbay/RecordOnline.git .
```

<br />

### Copy .env

```bash
cp .env.prod .env

# Genereate random key and store it somewhere
# https://generate-random.org/laravel-key-generator

# Add key to .env file
nano .env
#APP_KEY=base64:aHI5YXQ2Z2F6Znp5eDU1NWR0c2d6M2FjMTF5NDFnNjY=

# Change app url

#APP_URL=https://sub.domain.com
#VITE_PUSHER_HOST=sub-ws.domain.com
#DB_PASSWORD=somehardpass
```

<br />

### Set default user for laravel docker

```bash
echo UID=$(id -u) >> .env && echo GID=$(id -g) >> .env
```

<br />

### If you want to change ports then add it to .env

```bash

nano .env

#HTTP_PORT=80
#PROXY_PORT=81
#HTTPS_PORT=443
#FORWARD_DB_PORT=5432
#PUSHER_PORT=6001
#PUSHER_METRICS_PORT=9601

```

<br/>

___
## Disable any servers (Apache, Nginx etc) if ports conflict or change port
___

<br />

### Install nvm and node

```bash
sudo apt install curl
curl https://raw.githubusercontent.com/creationix/nvm/master/install.sh | bash

# Reopen terminal

nvm install --lts

cd /var/www/html

npm ci
```

### Build assets

```sh
npm run build
```

### To start the server

```bash
docker-compose -f docker-compose.prod.yml up -d
```

<br />


### To rebuild and start the server

```bash
docker-compose -f docker-compose.prod.yml up -d --build
```

<br />

### To stop the server

```bash
docker-compose -f docker-compose.prod.yml down
```

<br />

### Setup database
```bash
#Enter to the shell
docker-compose -f docker-compose.prod.yml exec laravel sh

# In the shell

php artisan migrate

# then type yes

# Seed db with defaults

php artisan db:seed

# then type yes

# Make admin user

php artisan voyager:admin your@email.com --create

# To exit shell

exit
```

### Edit proxy.
1. Go to the siteurl:81 or if you changed PROXY_PORT to siteurl:(paste here PROXY_PORT)

1.  Credentials:
Email:    admin@example.com
Password: changeme

1. Don't forget to change them

1. Go to Hosts > Proxy Hosts

1. Add Proxy Host

#### Add proxy to main site

1. Enter Domain name from which requests will come (firstly forward domain name to ip of host computer) and press enter to save.

1. Forward host name: "laravel"
1. Enter port (By default 80)

1. Enable: Cache assets, Block Common Exploits, Websocket Support

1. Go to tab SSL

1. Request new SSL

1. Enable Force SSL, HTTP/2

1. Press Save button

1. Wait until it saves

#### Add proxy to websocket server

1. Enter Domain name from which requests will come (firstly forward domain name to ip of host computer) and press enter to save.

1. Forward host name: "soketi"
1. Enter port (By default 6001)

1. Enable: Block Common Exploits, Websocket Support

1. Go to tab SSL

1. Request new SSL

1. Enable Force SSL, HTTP/2

1. Press Save button

1. Wait until it saves


### And finally, edit admin panel.

1. Go to /admin
1. Log in to your account
1. Go to the BREAD page
1. Edit recors_statuses
1. Save without changes
1. Go to Roles and change admin
1. Give all permission to Record Status
1. Go to the BREAD page
1. Add BREAD to subscriptions
1. Change model from "App\Subscription" to -> "App\Models\Subscription" 
1. Save it.
1. View it and add some Subscriptions (Абонементы)
1. Edit config as you want
