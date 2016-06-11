SHELL = /bin/bash

.PHONY: install docker provision start compose

compose:
	@echo "Generating configuration files for the containers..."
	@source containers/.env.sh; rm -f containers/docker-compose.yml; CONTAINER_VARS='$$CONTAINERS_PREFIX:$$MYSQL_ROOT_PASSWORD:$$MYSQL_USER:$$MYSQL_PASSWORD:$$MYSQL_DATABASE'; envsubst "$$CONTAINER_VARS" < "containers/templates/docker-compose.yml.template" > "containers/docker-compose.yml";
	@source containers/.env.sh; rm -f containers/dev/Dockerfile; CONTAINER_VARS='$$DEV_USER_ID:$$DEV_GROUP_ID:$$DEV_USER'; envsubst "$$CONTAINER_VARS" < "containers/dev/templates/Dockerfile.template" > "containers/dev/Dockerfile";
	@source containers/.env.sh; rm -f containers/dev/config/group.sh; CONTAINER_VARS='$$DEV_GROUP_ID:$$DEV_USER'; envsubst "$$CONTAINER_VARS" < "containers/dev/templates/group.sh.template" > "containers/dev/config/group.sh";
	@source containers/.env.sh; rm -f containers/dev/config/.bashrc; CONTAINER_VARS='$$DEV_HOSTNAME'; envsubst "$$CONTAINER_VARS" < "containers/dev/templates/.bashrc.template" > "containers/dev/config/.bashrc";
	@source containers/.env.sh; rm -f containers/dev/config/auth.json; CONTAINER_VARS='$$GITHUB_TOKEN'; envsubst "$$CONTAINER_VARS" < "containers/dev/templates/auth.json.template" > "containers/dev/config/auth.json";
	@source containers/.env.sh; rm -f applications/setup/.env; CONTAINER_VARS='$$APP_ENV:$$MYSQL_USER:$$MYSQL_PASSWORD:$$MYSQL_HOST'; envsubst "$$CONTAINER_VARS" < "containers/dev/templates/.env.template" > "applications/setup/.env";

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
