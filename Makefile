up:
	docker-compose -f .docker/docker-compose.yml up -d;

build:
	docker-compose -f .docker/docker-compose.yml up -d --build;

down:
	docker-compose -f .docker/docker-compose.yml down;

php:
	docker-compose -f .docker/docker-compose.yml exec php sh;

start:
	make up
	touch config/local.neon
	docker-compose -f .docker/docker-compose.yml exec php composer install

cache:
	rm -rf temp/cache