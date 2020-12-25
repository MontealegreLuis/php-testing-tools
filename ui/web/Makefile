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
	@bin/codecept clean
	@bin/codecept run acceptance --steps
	@bin/infection --min-msi=40 --min-covered-msi=70 --threads=4

server:
	@echo "Starting the Web application in PHP's built-in server"
	@php -S localhost:8000 -t public

check:
	@echo "Looking for static analysis violations"
	@php -d memory_limit=-1 bin/phpstan analyse
	@echo "Looking for CS violations in src directory"
	@bin/php-cs-fixer fix -v --dry-run --using-cache=no --config=.php_cs
	@echo "Looking for CS violations in tests directory"
	@bin/php-cs-fixer fix -v --dry-run --using-cache=no --config=.php_cs_tests

format:
	@echo "Fixing CS violations in src directory"
	@bin/php-cs-fixer fix -v --using-cache=no --config=.php_cs
	@echo "Fixing CS violations in tests directory"
	@bin/php-cs-fixer fix -v --using-cache=no --config=.php_cs_tests
