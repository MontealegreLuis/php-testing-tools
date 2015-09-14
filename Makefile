SHELL = /bin/bash

.PHONY: install

install:
	@echo "Installing PHP dependencies..."
	@composer install
	@echo "Installing PhantomJS..."
	@npm install
	@echo "Setup database..."
	@bin/doctrine orm:schema-tool:create
	@echo "Configuring environment variables for development..."
	@cp .env.dist .env
	@echo "Done!"
