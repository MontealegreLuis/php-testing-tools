SHELL = /bin/bash

.PHONY: install local db

local: db install

db:
	@echo "Creating database user..."
	@php setup-database.php $(RUSER) $(RPSWD) $(HOST) $(USER) $(PSWD)

install:
	@echo "Installing PHP dependencies..."
	@composer install
	@echo "Setup database..."
	@bin/doctrine orm:schema-tool:create
	@echo "Seed database with initial information..."
	@src/EwalletApplication/Bridges/SymfonyConsole/Resources/bin/console_dev ewallet:seed
	@echo "Done!"
