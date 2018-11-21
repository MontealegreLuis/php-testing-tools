SHELL = /bin/bash

.PHONY: tests setup

tests:
	@echo "Running scenarios.."
	@bin/behat
	@echo "Running specs"
	@bin/phpspec run
	@echo "Running unit tests"
	@bin/phpunit --testdox

setup:
	@echo "Installing application dependencies"
	@composer install
