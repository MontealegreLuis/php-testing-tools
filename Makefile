SHELL = /bin/bash

.PHONY: env containers composer db tests cleanup

env:
	@echo "Copying default settings for the containers.."
	@cp containers/templates/.env.sh.template containers/.env.sh
	@echo "Do not forget to set your Github token in 'containers/.env.sh'"

containers:
	@echo "Generating docker-compose.yml..."
	@source containers/.env.sh; rm -f containers/docker-compose.yml; CONTAINER_VARS='$$CONTAINERS_PREFIX:$$MYSQL_ROOT_PASSWORD:$$MYSQL_USER:$$MYSQL_PASSWORD:$$MYSQL_DATABASE:$$RABBIT_MQ_USER:$$RABBIT_MQ_PASSWORD'; envsubst "$$CONTAINER_VARS" < "containers/templates/docker-compose.yml.template" > "containers/docker-compose.yml";
	@echo "Generating configuration for the 'dev' image/container/application..."
	@source containers/.env.sh; rm -f containers/images/dev/Dockerfile; CONTAINER_VARS='$$DEV_USER_ID:$$DEV_GROUP_ID:$$DEV_USER'; envsubst "$$CONTAINER_VARS" < "containers/images/dev/templates/Dockerfile.template" > "containers/images/dev/Dockerfile";
	@source containers/.env.sh; rm -f containers/images/dev/config/group.sh; CONTAINER_VARS='$$DEV_GROUP_ID:$$DEV_USER'; envsubst "$$CONTAINER_VARS" < "containers/images/dev/templates/group.sh.template" > "containers/images/dev/config/group.sh";
	@source containers/.env.sh; rm -f containers/images/dev/config/.bashrc; CONTAINER_VARS='$$DEV_HOSTNAME'; envsubst "$$CONTAINER_VARS" < "containers/images/dev/templates/.bashrc.template" > "containers/images/dev/config/.bashrc";
	@source containers/.env.sh; rm -f containers/images/dev/config/auth.json; CONTAINER_VARS='$$GITHUB_TOKEN'; envsubst "$$CONTAINER_VARS" < "containers/images/dev/templates/auth.json.template" > "containers/images/dev/config/auth.json";
	@source containers/.env.sh; rm -f applications/dev/.env; CONTAINER_VARS='$$APP_ENV:$$MYSQL_USER:$$MYSQL_PASSWORD:$$MYSQL_HOST'; envsubst "$$CONTAINER_VARS" < "containers/images/dev/templates/.env.template" > "applications/dev/.env";
	@cp containers/images/dev/templates/php.ini containers/images/dev/config/php.ini
	@echo "Generating configuration for the 'web' image/container/application..."
	@source containers/.env.sh; rm -f containers/images/web/config/entrypoint.sh; CONTAINER_VARS='$$DEV_GROUP_ID:$$DEV_USER'; envsubst "$$CONTAINER_VARS" < "containers/images/web/templates/entrypoint.sh.template" > "containers/images/web/config/entrypoint.sh";
	@source containers/.env.sh; rm -f containers/images/web/config/ewallet.conf; CONTAINER_VARS='$$APP_ENV:$$MYSQL_USER:$$MYSQL_PASSWORD:$$MYSQL_HOST'; envsubst "$$CONTAINER_VARS" < "containers/images/web/templates/ewallet.conf.template" > "containers/images/web/config/ewallet.conf";
	@cp containers/images/web/templates/php.ini containers/images/web/config/php.ini
	@cp containers/templates/index_dev.php applications/web/public/index_dev.php
	@echo "Generating configuration for the 'console' image/container/application..."
	@source containers/.env.sh; rm -f applications/console/.env; CONTAINER_VARS='$$APP_ENV:$$MYSQL_USER:$$MYSQL_PASSWORD:$$MYSQL_HOST:$$MYSQL_DATABASE'; envsubst "$$CONTAINER_VARS" < "containers/images/console/templates/.env.template" > "applications/console/.env";
	@cp containers/images/console/templates/php.ini containers/images/console/config/php.ini
	@echo "Generating configuration for the 'messaging' image/container/application..."
	@source containers/.env.sh; rm -f applications/messaging/.env; CONTAINER_VARS='$$APP_ENV:$$MYSQL_USER:$$MYSQL_PASSWORD:$$MYSQL_HOST:$$MYSQL_DATABASE:$$RABBIT_MQ_USER:$$RABBIT_MQ_PASSWORD:$$RABBIT_MQ_HOST'; envsubst "$$CONTAINER_VARS" < "containers/images/messaging/templates/.env.template" > "applications/messaging/.env";
	@cp containers/images/messaging/templates/php.ini containers/images/messaging/config/php.ini
	@cp containers/images/messaging/templates/messaging-cron containers/images/messaging/config/messaging-cron
	@cp containers/templates/options.php applications/messaging/options.php
	@echo "Copying shared database configuration files.."
	@cp containers/templates/cli-config.php applications/dev/cli-config.php
	@cp containers/templates/cli-config.php applications/messaging/cli-config.php
	@cp containers/templates/cli-config.php packages/ewallet/doctrine/cli-config.php
	@cp containers/templates/cli-config.php packages/hexagonal/doctrine/cli-config.php
	@echo "Building containers..."
	@docker-compose -f containers/docker-compose.yml up -d

composer:
	@echo "Installing PHP dependencies..."
	@echo "Setting up applications..."
	@echo "Dev application..."
	@composer install --no-interaction -d applications/dev
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
	@echo "ewallet/definitions..."
	@composer install --no-interaction -d packages/ewallet/definitions
	@echo "hexagonal/doctrine..."
	@composer install --no-interaction -d packages/hexagonal/doctrine
	@echo "hexagonal/messaging..."
	@composer install --no-interaction -d packages/hexagonal/messaging

cleanup:
	@echo "Removing packages from ewallet/console"
	@rm -rf applications/console/vendor
	@rm -f applications/console/bin/doctrine
	@rm -f applications/console/bin/doctrine.php
	@rm -f applications/console/bin/doctrine-dbal
	@rm -f applications/console/bin/phpunit
	@echo "Removing packages from ewallet/dev"
	@rm -rf applications/dev/vendor
	@rm -f applications/dev/bin/doctrine
	@rm -f applications/dev/bin/doctrine.php
	@rm -f applications/dev/bin/doctrine-dbal
	@echo "Removing packages from ewallet/messaging"
	@rm -rf applications/messaging/vendor
	@rm -f applications/messaging/bin/doctrine
	@rm -f applications/messaging/bin/doctrine.php
	@rm -f applications/messaging/bin/doctrine-dbal
	@rm -f applications/messaging/bin/phpunit
	@echo "Removing packages from ewallet/web"
	@rm -rf applications/web/vendor
	@rm -rf applications/web/bin
	@echo "Removing packages from ewallet/application"
	@rm -rf packages/ewallet/application/vendor
	@rm -rf packages/ewallet/application/bin
	@echo "Removing packages from ewallet/definitions"
	@rm -rf packages/ewallet/definitions/vendor
	@rm -rf packages/ewallet/definitions/bin
	@echo "Removing packages from ewallet/doctrine"
	@rm -rf packages/ewallet/doctrine/vendor
	@rm -rf packages/ewallet/doctrine/bin
	@echo "Removing packages from ewallet/domain"
	@rm -rf packages/ewallet/domain/vendor
	@rm -rf packages/ewallet/domain/bin
	@echo "Removing packages from ewallet/responder"
	@rm -rf packages/ewallet/responder/vendor
	@rm -rf packages/ewallet/responder/bin
	@echo "Removing packages from ewallet/templating"
	@rm -rf packages/ewallet/templating/vendor
	@rm -rf packages/ewallet/templating/bin
	@echo "Removing packages from ewallet/validation"
	@rm -rf packages/ewallet/validation/vendor
	@rm -rf packages/ewallet/validation/bin
	@echo "Removing packages from hexagonal/doctrine"
	@rm -rf packages/hexagonal/doctrine/vendor
	@rm -rf packages/hexagonal/doctrine/bin
	@echo "Removing packages from hexagonal/messaging"
	@rm -rf packages/hexagonal/messaging/vendor
	@rm -rf packages/hexagonal/messaging/bin

db:
	@echo "Creating database..."
	@cd applications/dev && bin/console ewallet:db:create
	@echo "Creating tables..."
	@cd applications/dev && bin/doctrine orm:schema-tool:update --force
	@echo "Seeding database with initial information..."
	@cd applications/dev && bin/console ewallet:db:seed
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
	@echo "ewallet/definitions..."
	@cd packages/ewallet/definitions && bin/phpunit --testdox
	@echo "hexagonal/doctrine..."
	@cd packages/hexagonal/doctrine && bin/phpunit --testdox
	@echo "hexagonal/messaging..."
	@cd packages/hexagonal/messaging && bin/phpunit --testdox
	@echo "Running tests for applications..."
	@echo "Console application"
	@cd applications/console && bin/phpunit --testdox
	@echo "Messaging application"
	@cd applications/messaging && bin/phpunit --testdox
	@echo "Web application"
	@cd applications/web && bin/phpunit --testdox
	@cd applications/web && bin/robo acceptance
	@echo "Done!"
