# Makefile configuration
.DEFAULT_GOAL := help

help:
	@grep --extended-regexp '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

all: lint psr analyse

analyse: ## Runs phpStan static analysator
	@vendor/bin/phpstan analyse src --autoload-file=vendor/autoload.php --no-interaction --level=2 --memory-limit=32M

psr: ## Runs PSR2 check
	@vendor/bin/phpcs --config-set show_progress 1
	@vendor/bin/phpcs --standard=PSR2 src/
	@vendor/bin/phpcs --config-set show_progress 0

lint: ## Run PHP linting over folder src/
	@echo "Running PHP lint, it may take a while ..."
	@! find src/ -type f -name '*.php' -exec php -l {} \; |grep -v "No syntax errors detected"
	@echo "... PHP lint finished"
