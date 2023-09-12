PHP_EXEC=php

default: help

help: # Show help for each of the Makefile recipes
	@grep -E '^[a-zA-Z0-9 -]+:.*#'  Makefile | sort | while read -r l; do printf "\033[1;32m$$(echo $$l | cut -f 1 -d':')\033[00m:$$(echo $$l | cut -f 2- -d'#')\n"; done

build: composer-install # Build the project

composer-install: # Install composer deps
	$(PHP_EXEC) composer install

fix: # Run the code fixer
	@$(PHP_EXEC) ./vendor/bin/php-cs-fixer fix --allow-risky yes

#phpstan:
#	@$(PHP_EXEC) ./vendor/bin/phpstan analyze --memory-limit=1G

test: # Run tests
	@$(PHP_EXEC) ./vendor/bin/phpunit
