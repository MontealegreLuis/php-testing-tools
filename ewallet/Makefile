SHELL = /bin/bash

.PHONY: bootstrap setup tests

bootstrap:
	@echo "Installing application dependencies"
	@composer install

setup:
	@echo "Creating .env file for testing"
	@cp .env.dist .env.tests

tests:
	@echo "Running scenarios.."
	@vendor/bin/behat
	@echo "Running specs"
	@vendor/bin/phpspec run
	@echo "Running unit tests"
	@vendor/bin/phpunit --testdox
