SHELL = /bin/bash

.PHONY: help
help: ## Show help
	@echo Please specify a build target. The choices are:
	@grep -E '^[0-9a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: bootstrap
bootstrap: ## Install Composer dependencies
	@echo "Installing application dependencies"
	@composer install

.PHONY: setup
setup: ## Create configuration files
	@echo "Creating .env file for local development"
	@cp .env.dist .env

.PHONY: tests
tests: ## Run all test suites
	@echo "Running unit tests"
	@vendor/bin/phpunit --testdox
	@vendor/bin/infection --initial-tests-php-options='-d extension=pcov.so' --min-msi=65 --min-covered-msi=70 --threads=4

.PHONY: check
check: ## Run all code quality checks
	@echo "Looking for static analysis violations"
	@vendor/bin/phpstan analyse
	@echo "Looking for CS violations in src directory"
	@vendor/bin/php-cs-fixer fix -v --dry-run --using-cache=no --config=.php_cs
	@echo "Looking for CS violations in tests directory"
	@vendor/bin/php-cs-fixer fix -v --dry-run --using-cache=no --config=.php_cs_tests
	@echo "Looking for missing soft dependencies"
	@vendor/bin/composer-require-checker check --config-file=require-checker.json composer.json

.PHONY: format
format: ## Execute CS formatting
	@echo "Fixing CS violations in src directory"
	@vendor/bin/php-cs-fixer fix -v --using-cache=no --config=.php_cs
	@echo "Fixing CS violations in tests directory"
	@vendor/bin/php-cs-fixer fix -v --using-cache=no --config=.php_cs_tests
