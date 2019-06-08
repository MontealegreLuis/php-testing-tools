SHELL = /bin/bash

.PHONY: bootstrap setup tests check format

bootstrap:
	@echo "Installing application dependencies"
	@composer install

setup:
	@echo "Creating .env file for local development"
	@cp .env.dist .env

tests:
	@echo "Running unit tests"
	@vendor/bin/phpunit --testdox
	@echo "Running mutation tests"
	@vendor/bin/infection --min-msi=75 --min-covered-msi=85 --threads=4

check:
	@echo "Looking for static analysis violations"
	@vendor/bin/phpstan analyse src --level 7
	@echo "Looking for CS violations in src directory"
	@vendor/bin/php-cs-fixer fix -v --dry-run --using-cache=no --config=.php_cs
	@echo "Looking for CS violations in tests directory"
	@vendor/bin/php-cs-fixer fix -v --dry-run --using-cache=no --config=.php_cs_tests

format:
	@echo "Fixing CS violations in src directory"
	@vendor/bin/php-cs-fixer fix -v --using-cache=no --config=.php_cs
	@echo "Fixing CS violations in tests directory"
	@vendor/bin/php-cs-fixer fix -v --using-cache=no --config=.php_cs_tests

