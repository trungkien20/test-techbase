.PHONY: up down nginx php phplog nginxlog db coverage vendor

MAKEPATH := $(abspath $(lastword $(MAKEFILE_LIST)))
PWD := $(dir $(MAKEPATH))
CONTAINERS := $(shell docker ps -a -q -f "name=test*")

up:
	docker-compose up -d --build

down:
	docker-compose down

nginx:
	docker exec -it test-nginx-container bash

php: 
	docker exec -it test-php-container bash

phplog: 
	docker logs test-php-container

nginxlog:
	docker logs test-nginx-container

db:
	docker-compose exec mysql mysql -e 'DROP DATABASE IF EXISTS test_api ; CREATE DATABASE test_api;'
	docker-compose exec mysql sh -c "mysql test_api < docker-entrypoint-initdb.d/database.sql"

coverage:
	docker-compose exec php-fpm sh -c "./vendor/bin/phpunit --coverage-text --coverage-html coverage"

vendor:
	docker-compose exec php-fpm sh -c "composer install"
