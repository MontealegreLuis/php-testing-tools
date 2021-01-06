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
	@vendor/bin/infection --initial-tests-php-options='-d extension=pcov.so' --min-msi=65 --min-covered-msi=75 --threads=4

check:
	@echo "Looking for static analysis violations"
	@vendor/bin/phpstan analyse
	@echo "Looking for CS violations in src directory"
	@vendor/bin/php-cs-fixer fix -v --dry-run --using-cache=no --config=.php_cs
	@echo "Looking for CS violations in tests directory"
	@vendor/bin/php-cs-fixer fix -v --dry-run --using-cache=no --config=.php_cs_tests

format:
	@echo "Fixing CS violations in src directory"
	@vendor/bin/php-cs-fixer fix -v --using-cache=no --config=.php_cs
	@echo "Fixing CS violations in tests directory"
	@vendor/bin/php-cs-fixer fix -v --using-cache=no --config=.php_cs_tests
