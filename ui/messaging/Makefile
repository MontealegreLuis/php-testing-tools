SHELL = /bin/bash

.PHONY: bootstrap setup tests

bootstrap:
	@echo "Installing application dependencies"
	@composer install

setup:
	@echo "Creating .env file for local development"
	@cp .env.dist .env

tests:
	@echo "Running unit tests"
	@vendor/bin/phpunit --testdox
