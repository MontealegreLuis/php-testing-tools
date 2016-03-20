SHELL = /bin/bash

.PHONY: install docker provision start

install:
	@echo "Installing PHP dependencies..."
	@echo "Setting up applications..."
	@echo "Setup application..."
	@composer install --no-interaction -d applications/setup
	@echo "Messaging application..."
	@composer install --no-interaction -d applications/messaging
	@echo "Console application..."
	@composer install --no-interaction -d applications/console
	@echo "Web application..."
	@composer install --no-interaction -d applications/web
	@echo "Setting up packages..."
	@echo "ewallet/domain..."
	@composer install --no-interaction -d packages/ewallet/domain
	@echo "ewallet/application..."
	@composer install --no-interaction -d packages/ewallet/application
	@echo "ewallet/responder..."
	@composer install --no-interaction -d packages/ewallet/responder
	@echo "ewallet/templating..."
	@composer install --no-interaction -d packages/ewallet/templating
	@echo "ewallet/validation..."
	@composer install --no-interaction -d packages/ewallet/validation
	@echo "ewallet/doctrine..."
	@composer install --no-interaction -d packages/ewallet/doctrine
	@echo "ewallet/hexagonal..."
	@composer install --no-interaction -d packages/hexagonal/doctrine
	@echo "Creating database..."
	@cd applications/setup && bin/console ewallet:db:create
	@echo "Creating tables..."
	@cd applications/setup && bin/doctrine orm:schema-tool:update --force
	@echo "Seeding database with initial information..."
	@cd applications/setup && bin/console ewallet:db:seed
	@echo "Done!"

tests:
	@echo "Running tests for packages..."
	@echo "ewallet/domain..."
	@cd packages/ewallet/domain && bin/behat
	@cd packages/ewallet/domain && bin/phpspec run
	@cd packages/ewallet/domain && bin/phpunit --testdox
	@echo "ewallet/application..."
	@cd packages/ewallet/application && bin/phpunit --testdox
	@echo "ewallet/responder..."
	@cd packages/ewallet/responder && bin/phpunit --testdox
	@echo "ewallet/templating..."
	@cd packages/ewallet/templating && bin/phpunit --testdox
	@echo "ewallet/validation..."
	@cd packages/ewallet/validation && bin/phpunit --testdox
	@echo "ewallet/doctrine..."
	@cd packages/ewallet/doctrine && bin/phpunit --testdox
	@echo "hexagonal/doctrine..."
	@cd packages/hexagonal/doctrine && bin/phpunit --testdox
	@echo "Done!"

docker: provision start

provision:
	@echo "Downloading and building containers."
	@ansible-playbook containers/provision.yml --extra-vars "GITHUB_TOKEN=$(GTOKEN)"

start:
	@echo "Starting containers"
	@ansible-playbook containers/start.yml
