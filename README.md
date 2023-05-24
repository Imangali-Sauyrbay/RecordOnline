echo UID=$(id -u) >> .env && echo GID=$(id -g) >> .env

docker-compose -f docker-compose.prod.yml up
