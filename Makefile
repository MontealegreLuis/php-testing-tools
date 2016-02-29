SHELL = /bin/bash

.PHONY: install docker docker-build docker-run web

install:
	@echo "Installing PHP dependencies..."
	@composer install --no-interaction -d applications/setup
	@echo "Messaging application..."
	@composer install --no-interaction -d applications/messaging
	@echo "Console application..."
	@composer install --no-interaction -d applications/console
	@echo "Web application..."
	@composer install --no-interaction -d applications/web
	@echo "Creating database..."
	@cd applications/setup && bin/console ewallet:db:create
	@echo "Creating tables..."
	@cd applications/setup && bin/doctrine orm:schema-tool:update --force
	@echo "Seeding database with initial information..."
	@cd applications/setup && bin/console ewallet:db:seed
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
