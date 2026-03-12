CONTAINER_NAME = php-fpm

start:
	sudo docker-compose build && sudo docker-compose up -d && sudo docker-compose exec $(CONTAINER_NAME) php artisan migrate

bash:
	sudo docker-compose exec $(CONTAINER_NAME) bash

restart: down start

down:
	sudo docker-compose down

ps:
	sudo docker-compose ps

network:
	sudo docker network create expeditors

check:
	php vendor/bin/grumphp run
