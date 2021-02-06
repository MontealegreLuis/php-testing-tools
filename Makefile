SHELL = /bin/bash

.PHONY: help
help: ## Show help
	@echo Please specify a build target. The choices are:
	@grep -E '^[0-9a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: containers
containers: ## Build all containers
	@echo "Building containers..."
	@docker-compose build

.PHONY: bootstrap
bootstrap: ## Install PHP dependencies
	@echo "Installing PHP dependencies..."
	@echo "Console application..."
	@composer install --no-interaction -d ui/console
	@echo "Messaging application..."
	@composer install --no-interaction -d ui/messaging
	@echo "Web application..."
	@composer install --no-interaction -d ui/web
	@echo "ewallet/application..."
	@composer install --no-interaction -d ewallet

.PHONY: cleanup
cleanup: ## Remove PHP dependencies
	@echo "Removing packages from ui/console"
	@rm -rf ui/console/vendor
	@rm -f ui/console/composer.lock
	@echo "Removing packages from ui/messaging"
	@rm -rf ui/messaging/vendor
	@rm -f ui/messaging/composer.lock
	@echo "Removing packages from ui/web"
	@rm -rf ui/web/vendor
	@rm -rf ui/web/bin
	@rm -f ui/web/composer.lock
	@echo "Removing packages from ewallet/application"
	@rm -rf ewallet/vendor
	@rm -f ewallet/composer.lock

.PHONY: setup
setup: ## Create and seed database
	@echo "Creating database..."
	@cd ewallet && bin/setup ewallet:db:create
	@echo "Creating tables..."
	@cd ewallet && bin/setup orm:schema-tool:update --force
	@echo "Seeding database with initial information..."
	@cd ewallet && bin/setup ewallet:db:seed
	@echo "Done!"

.PHONY: tests
tests: ## Run all test suites
	@echo "Running tests for packages..."
	@echo "ewallet/application..."
	@cd ewallet && make tests
	@echo "Console application"
	@cd ui/console && make tests
	@echo "Messaging application"
	@cd ui/messaging && make tests
	@echo "Web application"
	@cd ui/web && make tests
	@echo "Done!"

.PHONY: check
check: ## Run all code quality checks and test suites
	@echo "Running code quality checks..."
	@echo "ewallet/application..."
	@cd ewallet && make check && make tests
	@echo "Console application"
	@cd ui/console && make check && make tests
	@echo "Messaging application"
	@cd ui/messaging && make check && make tests
	@echo "Web application"
	@cd ui/web && make check && make tests
	@echo "Done!"

.PHONY: stop
stop: ## Stop all Docker containers
	@echo "Stopping all Docker containers"
	@docker-compose stop
