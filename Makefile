SHELL = /bin/bash

.PHONY: install phantom

install:
	@echo "Installing PHP dependencies..."
	@composer install
	@echo "Setup database..."
	@bin/doctrine orm:schema-tool:create
	@echo "Done!"

phantom:
	@echo "Installing PhantomJS..."
	@npm install
