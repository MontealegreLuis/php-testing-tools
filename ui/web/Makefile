SHELL = /bin/bash

.PHONY: bootstrap setup tests server check format

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

check:
	@echo "Looking for static analysis violations"
	@bin/phpstan analyse src --level 7
	@echo "Looking for CS violation in src directory"
	@bin/php-cs-fixer fix -v --dry-run --using-cache=no --config=.php_cs
	@echo "Looking for CS violation in tests directory"
	@bin/php-cs-fixer fix -v --dry-run --using-cache=no --config=.php_cs_tests

format:
	@echo "Fixing CS violation in src directory"
	@bin/php-cs-fixer fix -v --using-cache=no --config=.php_cs
	@echo "Fixing CS violation in tests directory"
	@bin/php-cs-fixer fix -v --using-cache=no --config=.php_cs_tests
