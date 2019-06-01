SHELL = /bin/bash

.PHONY: containers bootstrap db tests cleanup check

containers:
	@echo "Building containers..."
	@docker-compose build

bootstrap:
	@echo "Installing PHP dependencies..."
	@echo "Messaging application..."
	@composer install --no-interaction -d ui/messaging
	@echo "Console application..."
	@composer install --no-interaction -d ui/console
	@echo "Web application..."
	@composer install --no-interaction -d ui/web
	@echo "ewallet/application..."
	@composer install --no-interaction -d ewallet

cleanup:
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

setup:
	@echo "Creating database..."
	@cd ewallet && bin/setup ewallet:db:create
	@echo "Creating tables..."
	@cd ewallet && bin/setup orm:schema-tool:update --force
	@echo "Seeding database with initial information..."
	@cd ewallet && bin/setup ewallet:db:seed
	@echo "Done!"

tests:
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

check:
	@echo "Running tests for packages..."
	@echo "ewallet/application..."
	@cd ewallet && make check && make tests
	@echo "Console application"
	@cd ui/console && make check && make tests
	@echo "Messaging application"
	@cd ui/messaging && make check && make tests
	@echo "Web application"
	@cd ui/web && make check && make tests
	@echo "Done!"
