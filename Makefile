SHELL = /bin/bash

.PHONY: install local db docker docker-build docker-run web

local: db install

db:
	@echo "Creating database user..."
	@php setup-database.php $(RUSER) $(RPSWD) $(HOST) $(USER) $(PSWD)

install:
	@echo "Installing PHP dependencies..."
	@composer install --no-interaction
	@echo "Setup database..."
	@bin/doctrine orm:schema-tool:update --force
	@echo "Seed database with initial information..."
	@src/EwalletApplication/Bridges/SymfonyConsole/Resources/bin/console_dev ewallet:seed
	@echo "Done!"

docker: docker-build docker-run

docker-build:
	@echo "Downloading and building containers."
	@ansible-playbook containers/provision.yml --extra-vars "GITHUB_TOKEN=$(GTOKEN)"

docker-run:
	@echo "Start containers"
	@ansible-playbook containers/start.yml

web:
	@echo "Running container for Web application."
	@ansible-playbook containers/web.yml
