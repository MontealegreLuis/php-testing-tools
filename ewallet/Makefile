SHELL = /bin/bash

.PHONY: tests setup

tests:
	@echo "Running scenarios.."
	@vendor/bin/behat
	@echo "Running specs"
	@vendor/bin/phpspec run
	@echo "Running unit tests"
	@vendor/bin/phpunit --testdox

setup:
	@echo "Installing application dependencies"
	@composer install
