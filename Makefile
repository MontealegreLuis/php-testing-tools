SHELL = /bin/bash

.PHONY: containers composer db tests cleanup

containers:
	@echo "Building containers..."
	@docker-compose -f containers/docker-compose.yml up -d

composer:
	@echo "Installing PHP dependencies..."
	@echo "Setting up applications..."
	@echo "Messaging application..."
	@composer install --no-interaction -d ui/messaging
	@echo "Console application..."
	@composer install --no-interaction -d ui/console
	@echo "Web application..."
	@composer install --no-interaction -d ui/web
	@echo "Setting up packages..."
	@echo "ewallet/application..."
	@composer install --no-interaction -d ewallet

cleanup:
	@echo "Removing packages from ui/console"
	@rm -rf ui/console/vendor
	@echo "Removing packages from ui/messaging"
	@rm -rf applications/messaging/vendor
	@echo "Removing packages from ui/web"
	@rm -rf applications/web/vendor
	@rm -rf applications/web/bin
	@echo "Removing packages from ewallet/application"
	@rm -rf ewallet/vendor

db:
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
	@cd ewallet && vendor/bin/behat
	@cd ewallet && vendor/bin/phpspec run
	@cd ewallet && vendor/bin/phpunit --testdox
	@echo "Console application"
	@cd ui/console && vendor/bin/phpunit --testdox
	@echo "Messaging application"
	@cd ui/messaging && vendor/bin/phpunit --testdox
	@echo "Web application"
	@cd ui/web && bin/phpunit --testdox
	@cd ui/web && bin/robo acceptance
	@echo "Done!"
