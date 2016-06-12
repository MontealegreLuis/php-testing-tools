SHELL = /bin/bash

.PHONY: install compose tests

compose:
	@echo "Generating docker-compose.yml..."
	@source containers/.env.sh; rm -f containers/docker-compose.yml; CONTAINER_VARS='$$CONTAINERS_PREFIX:$$MYSQL_ROOT_PASSWORD:$$MYSQL_USER:$$MYSQL_PASSWORD:$$MYSQL_DATABASE:$$RABBIT_MQ_USER:$$RABBIT_MQ_PASSWORD'; envsubst "$$CONTAINER_VARS" < "containers/templates/docker-compose.yml.template" > "containers/docker-compose.yml";
	@echo "Generating configuration for the 'dev' image/container/application..."
	@source containers/.env.sh; rm -f containers/dev/Dockerfile; CONTAINER_VARS='$$DEV_USER_ID:$$DEV_GROUP_ID:$$DEV_USER'; envsubst "$$CONTAINER_VARS" < "containers/dev/templates/Dockerfile.template" > "containers/dev/Dockerfile";
	@source containers/.env.sh; rm -f containers/dev/config/group.sh; CONTAINER_VARS='$$DEV_GROUP_ID:$$DEV_USER'; envsubst "$$CONTAINER_VARS" < "containers/dev/templates/group.sh.template" > "containers/dev/config/group.sh";
	@source containers/.env.sh; rm -f containers/dev/config/.bashrc; CONTAINER_VARS='$$DEV_HOSTNAME'; envsubst "$$CONTAINER_VARS" < "containers/dev/templates/.bashrc.template" > "containers/dev/config/.bashrc";
	@source containers/.env.sh; rm -f containers/dev/config/auth.json; CONTAINER_VARS='$$GITHUB_TOKEN'; envsubst "$$CONTAINER_VARS" < "containers/dev/templates/auth.json.template" > "containers/dev/config/auth.json";
	@source containers/.env.sh; rm -f applications/dev/.env; CONTAINER_VARS='$$APP_ENV:$$MYSQL_USER:$$MYSQL_PASSWORD:$$MYSQL_HOST'; envsubst "$$CONTAINER_VARS" < "containers/dev/templates/.env.template" > "applications/dev/.env";
	@cp containers/dev/templates/php.ini containers/dev/config/php.ini
	@echo "Generating configuration for the 'web' image/container/application..."
	@source containers/.env.sh; rm -f containers/web/config/entrypoint.sh; CONTAINER_VARS='$$DEV_GROUP_ID:$$DEV_USER'; envsubst "$$CONTAINER_VARS" < "containers/web/templates/entrypoint.sh.template" > "containers/web/config/entrypoint.sh";
	@source containers/.env.sh; rm -f containers/web/config/ewallet.conf; CONTAINER_VARS='$$APP_ENV:$$MYSQL_USER:$$MYSQL_PASSWORD:$$MYSQL_HOST'; envsubst "$$CONTAINER_VARS" < "containers/web/templates/ewallet.conf.template" > "containers/web/config/ewallet.conf";
	@cp containers/web/templates/php.ini containers/web/config/php.ini
	@echo "Generating configuration for the 'console' image/container/application..."
	@source containers/.env.sh; rm -f applications/console/.env; CONTAINER_VARS='$$APP_ENV:$$MYSQL_USER:$$MYSQL_PASSWORD:$$MYSQL_HOST'; envsubst "$$CONTAINER_VARS" < "containers/console/templates/.env.template" > "applications/console/.env";
	@cp containers/console/templates/php.ini containers/console/config/php.ini
	@echo "Generating configuration for the 'messaging' image/container/application..."
	@source containers/.env.sh; rm -f applications/messaging/.env; CONTAINER_VARS='$$APP_ENV:$$MYSQL_USER:$$MYSQL_PASSWORD:$$MYSQL_HOST:$$RABBIT_MQ_USER:$$RABBIT_MQ_PASSWORD:$$RABBIT_MQ_HOST'; envsubst "$$CONTAINER_VARS" < "containers/messaging/templates/.env.template" > "applications/messaging/.env";
	@cp containers/messaging/templates/php.ini containers/messaging/config/php.ini
	@cp containers/messaging/templates/messaging-cron containers/messaging/config/messaging-cron
	@echo "Building containers..."
	@docker-compose -f containers/docker-compose.yml up -d

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
