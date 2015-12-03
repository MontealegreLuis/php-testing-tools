SHELL = /bin/bash

.PHONY: install phantom

install:
	@echo "Seed database with initial information..."
	@src/EwalletApplication/Bridges/SymfonyConsole/Resources/bin/console_dev ewallet:seed
	@echo "Done!"

phantom:
	@echo "Installing PhantomJS..."
	@npm install
