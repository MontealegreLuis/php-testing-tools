SHELL = /bin/bash

.PHONY: bootstrap setup tests server

bootstrap:
	@echo "Installing application dependencies"
	@composer install

setup:
	@echo "Creating .env file for local development"
	@cp .env.dist .env

tests:
	@echo "Running unit tests"
	@bin/phpunit --testdox
	@echo "Running end to end tests"
	@bin/robo acceptance

server:
	@Starting the Web application in PHP's built-in server
	@php -S localhost:8000 -t public
