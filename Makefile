SHELL = /bin/bash

.PHONY: install local

local:
    @echo "Creating database user..."
    @php create-user.php $(RUSER) $(RPSWD) $(HOST) $(DB) $(USER) $(PSWD)
    install

install:
	@echo "Installing PHP dependencies..."
	@composer install
	@echo "Setup database..."
	@bin/doctrine orm:schema-tool:create
	@echo "Seed database with initial information..."
	@src/EwalletApplication/Bridges/SymfonyConsole/Resources/bin/console_dev ewallet:seed
	@echo "Done!"
