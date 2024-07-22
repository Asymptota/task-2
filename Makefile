.PHONY: *
.DEFAULT_GOAL := help

MAKEFILE_PATH := $(abspath $(lastword $(MAKEFILE_LIST)))
BACKEND_DIR := $(shell dirname $(realpath $(firstword $(MAKEFILE_LIST))))

init:
	docker compose build --no-cache
dev:
	docker compose up --pull always -d --wait

install-in-docker-container:
	docker-compose exec php-fpm make install

install-composer:
	@echo "Backend install composer"
	cd $(BACKEND_DIR) && composer install

install: install-composer

tests-in-docker-container:
	docker-compose exec php-fpm make tests

tests:
	@echo "PHPUnit test"
	cd $(BACKEND_DIR) && ./vendor/bin/phpunit --colors -vvvv tests/

check-phpstan:
	@echo "PHPStand check"
	cd $(BACKEND_DIR) && ./vendor/bin/phpstan analyse

