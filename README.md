## Technologies Used

   - Docker
   - FROM nginx:1.18-alpine
   - FROM php:8.4-fpm
   - FROM mysql:8.0
   - FROM phpMyAdmin
   - Laravel v10.50.2 (PHP v8.4.21)

## Project Installation

After 'git clone':

Make sure you have the right path in "docker-compose.yml:
```
services:
  # php (app)
  app:
    build:
      context: ./docker/php
    volumes:
      - ./app:/var/www/crm/app/public
    networks:
      - news-network

  # nginx
  nginx:
    build:
      context: ./docker/nginx
    ports:
      - '8080:80'
    volumes:
      - ./app:/var/www/crm/app/public
```
```
$ make init
```
```
$ make up
```

If your docker project lives at - ***/var/www/crm*** (linux)
- in CLI from there:
   * `docker exec -it crm-app-1 chown -R www-data:www-data storage bootstrap/cache`
   * `docker exec -it crm-app-1 composer install`
   * `docker exec -it crm-app-1 php artisan key:generate`
   * `docker exec -it crm-app-1 php artisan migrate`

 - .. or from the /var/www/crm/app directory:
   * `sudo chown -R $USER:www-data storage bootstrap/cache`
   * `sudo chmod -R 775 storage bootstrap/cache`

## app
http://127.0.0.1:8080/

## phpmyadmin
http://127.0.0.1:8081/
   - `db`
   - `root`
   - `root`

## Command Glossary
   - `make init`
   - `make down`
   - `make up`
   - `make restart` - rebuild and start containers
   - `docker ps` - list all running containers
   - `docker exec -it <NAME> sh` - Enter container
   - `docker logs -f <container_id>` - see incomming logs for container
   - Show tree Directory/Files in a container: 
     ``` $ find . -print | sed -e 's;[^/]*/;|____;g;s;____|; |;g'```

## Development Notes
  - Project architecture comes with Docker environment and Laravel Application.
  - 