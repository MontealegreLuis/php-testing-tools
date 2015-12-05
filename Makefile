SHELL = /bin/bash

.PHONY: install local db docker docker-build docker-run docker-setup web

local: db install

db:
	@echo "Creating database user..."
	@php setup-database.php $(RUSER) $(RPSWD) $(HOST) $(USER) $(PSWD)

install:
	@echo "Installing PHP dependencies..."
	@composer install --no-interaction
	@echo "Setup database..."
	@bin/doctrine orm:schema-tool:create
	@echo "Seed database with initial information..."
	@src/EwalletApplication/Bridges/SymfonyConsole/Resources/bin/console_dev ewallet:seed
	@echo "Done!"

docker: docker-build docker-run docker-setup

docker-build:
	@echo "Downloading and building containers."
	@ansible-playbook ansible/provision.yml

docker-run:
	@echo "Start containers"
	@ansible-playbook ansible/start.yml

docker-setup:
	@echo "Setting up application and starting containers."
	@ansible-playbook ansible/setup.yml -v

web:
	@echo "Running container for Web application."
	@ansible-playbook ansible/web.yml
