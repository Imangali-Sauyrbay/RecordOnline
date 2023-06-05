# Ways to host server:
### 1) With docker compose by using node.js websocket server

### 2) With docker compose WITHOUT using node.js websocket server

### 3) On the apache server as an standart laravel app

### 4) On the nginx server as an standart laravel app
---
## Notice!
This server uses [soketi](https://docs.soketi.app) as a websoket server. so you need to host it too. Or just use pre-configured docker. <br/>
**BUT** you can disable it with environment variable WS and set prefered time for long polling!

---
# Steps to host in docker container:

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

#!!! DON'T USE IT. IT IS JUST AN EXAMPLE. GENERATE NEW ONE
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
#WS=true
#VITE_POLLING_SECONDS=5
```

<br/>

___
## Disable any servers (Apache, Nginx etc) if ports conflict or change port
___

<br />

### To start the server

- plain - only server and database
- proxy - adds proxy that can server over https, block common exploits and caches data
- ws - adds proxy and websocket server soketi

```bash
docker-compose -f docker-compose.prod.[ws/proxy/plain].yml up -d
```

<br />


### To rebuild and start the server

```bash
docker-compose -f docker-compose.prod.[ws/proxy/plain].yml up -d --build
```

<br />

### To stop the server

```bash
docker-compose -f docker-compose.prod.[ws/proxy/plain].yml down
```

<br />

### Rebuild and start the server and Setup database
```bash
#Enter to the shell
docker-compose -f docker-compose.prod.ws.yml exec laravel sh

# In the shell

php artisan migrate

# then type yes

# Seed db with defaults

php artisan db:seed

# then type yes

# Make admin user

php artisan voyager:admin your@email.com --create

# Make sure to reload server
php artisan octane:reload

# To exit shell

exit
```

### Edit proxy.
1. Go to the host_ip:81 or if you changed PROXY_PORT to host_ip:(paste here PROXY_PORT)

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

1. Enable: Cache assets, Block Common Exploits

1. Go to tab SSL

1. SSL Certificate

1. Request new SSL

1. Enable Force SSL, HTTP/2

1. Press Save button

1. Wait until it saves

#### Add proxy to websocket server

1. Add Proxy Host

1. Enter Domain name from which requests will come (firstly forward domain name to ip of host computer) and press enter to save.

1. Forward host name: "soketi"
1. Enter port (By default 6001)

1. Enable: Block Common Exploits, Websocket Support

1. Go to tab SSL

1. Request new SSL

1. Enable Force SSL, HTTP/2

1. Press Save button

1. Wait until it saves

#### If you want, you can additionaly add proxy url to manager
1. Just add domain like sub-proxy.domain.com.
1. forward it to localhost:81
1. enable Cache assets, Block Common Exploits, Force SSL, HTTP/2
1. enable ssl and save

#### if your memory is full try force delete all docker caches/images/layers (You need rebuild after this!!!)
##### it wont remove server datas like proxy config / data from database. It all stored in ./storage/volumes in server folder! 
```bash
docker system prune --all --force
```

<br/>
<br/>

# Steps to host on apache2:

1. **Prerequisites**:
   - Make sure you have Apache web server installed and running.
   - Ensure that PHP is installed on your server and configured properly.

2. **Clone or Upload the Laravel Application**:
   - Clone your Laravel application repository to your server's desired location or upload the application files to your server using FTP or any other method.

3. **Install Dependencies**:
   - Navigate to the root directory of your Laravel application.
   - Run `composer install` to install the required dependencies.

4. **Configure Apache Virtual Host**:
   - Create an Apache virtual host configuration file for your Laravel application. You can create a new file, such as `laravel-app.conf`, in the Apache configuration directory or modify the default configuration file.
   - Add the following configuration to the virtual host file:

      ```apache
      <VirtualHost *:80>
          ServerName your-domain-or-ip
          DocumentRoot /path/to/laravel-app/public

          <Directory /path/to/laravel-app/public>
              AllowOverride All
              Options FollowSymlinks
              Require all granted
          </Directory>

          ErrorLog ${APACHE_LOG_DIR}/laravel-app-error.log
          CustomLog ${APACHE_LOG_DIR}/laravel-app-access.log combined
      </VirtualHost>
      ```

      Replace `your-domain-or-ip` with your actual domain name or server IP address, and `/path/to/laravel-app` with the path to your Laravel application directory.

5. **Enable the Virtual Host**:
   - Enable the Laravel application virtual host by creating a symbolic link in the Apache `sites-enabled` directory. Run the following command:

      ```bash
      sudo ln -s /etc/apache2/sites-available/laravel-app.conf /etc/apache2/sites-enabled/
      ```

      Replace `/etc/apache2/sites-available/laravel-app.conf` with the actual path to your virtual host configuration file.

6. **Enable Required Apache Modules**:
   - Enable the necessary Apache modules by running the following commands:

      ```bash
      sudo a2enmod rewrite
      sudo service apache2 restart
      ```

7. **Set Proper File Permissions**:
   - Ensure that the appropriate permissions are set for Laravel directories. Run the following commands from your Laravel application root directory:

      ```bash
      sudo chown -R www-data:www-data storage bootstrap/cache
      sudo chmod -R 775 storage bootstrap/cache
      ```

8. **Restart Apache**:
   - Restart the Apache server to apply the changes:

      ```bash
      sudo service apache2 restart
      ```

9. **Access Your Laravel Application**:
   - Now, you should be able to access your Laravel application by visiting the domain or IP address you specified in the virtual host configuration.

<br/>
<br/>

# Steps to host on nginx server:

1. **Prerequisites**:
   - Make sure you have Nginx web server installed and running.
   - Ensure that PHP is installed on your server and configured properly.

2. **Clone or Upload the Laravel Application**:
   - Clone your Laravel application repository to your server's desired location or upload the application files to your server using FTP or any other method.

3. **Install Dependencies**:
   - Navigate to the root directory of your Laravel application.
   - Run `composer install` to install the required dependencies.

4. **Configure Nginx**:
   - Locate the Nginx configuration directory. It is typically located in `/etc/nginx/conf.d/` or `/etc/nginx/sites-available/`.
   - Create a new configuration file, such as `laravel-app.conf`, in the Nginx configuration directory or modify the default configuration file.
   - Add the following configuration to the file:

      ```nginx
      server {
          listen 80;
          server_name your-domain-or-ip;
          root /path/to/laravel-app/public;
          index index.php;

          location / {
              try_files $uri $uri/ /index.php?$query_string;
          }

          location ~ \.php$ {
              include fastcgi_params;
              fastcgi_pass unix:/run/php/php7.4-fpm.sock;  # Modify the PHP version if necessary
              fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
              fastcgi_param PATH_INFO $fastcgi_path_info;
          }
      }
      ```

      Replace `your-domain-or-ip` with your actual domain name or server IP address, and `/path/to/laravel-app` with the path to your Laravel application directory.

5. **Enable the Configuration**:
   - If you created a new configuration file, create a symbolic link in the Nginx `sites-enabled` directory. Run the following command:

      ```bash
      sudo ln -s /etc/nginx/conf.d/laravel-app.conf /etc/nginx/sites-enabled/
      ```

      Replace `/etc/nginx/conf.d/laravel-app.conf` with the actual path to your configuration file.

6. **Restart Nginx**:
   - Restart the Nginx server to apply the changes:

      ```bash
      sudo service nginx restart
      ```

7. **Set Proper File Permissions**:
   - Ensure that the appropriate permissions are set for Laravel directories. Run the following commands from your Laravel application root directory:

      ```bash
      sudo chown -R www-data:www-data storage bootstrap/cache
      sudo chmod -R 775 storage bootstrap/cache
      ```

8. **Access Your Laravel Application**:
   - Now, you should be able to access your Laravel application by visiting the domain or IP address you specified in the Nginx configuration.


<br/>
<br/>
<br/>


# And finally, edit admin panel.

1. Go to siteurl/admin
1. Log in to your account
1. Go to the BREAD page (in the Instruments)
1. Edit recors_statuses
1. Save without changes
1. Go to Roles and edit admin
1. Give all permission to Record Status
1. Go to the BREAD page
1. Add BREAD to subscriptions
1. Change model from "App\Subscription" to -> "App\Models\Subscription" 
1. Save it.
1. View it and add some Subscriptions (Абонементы)
1. Edit config as you want
