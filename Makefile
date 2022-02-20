docker-app-exec = docker exec -it ghpvc-app /bin/sh -c

ssh-app: ## Connect to app container
	docker exec -it ghpvc-app /bin/sh

setup-dev: ## Setup project for development
	make start
	make composer-install

start: ## Start application silently
	docker-compose up -d

stop: ## Stop application
	docker-compose down

restart: ## Restart the application
	make stop
	make start

composer-install: ## Install composer dependencies
	$(docker-app-exec) 'composer install'

composer-update: ## Update composer dependencies
	$(docker-app-exec) 'composer update $(filter-out $@,$(MAKECMDGOALS))'

copy-env: ## Copy .env.example as .env
	cp .env.example .env

cleanup-docker: ## Remove old docker images
	docker rmi $$(docker images --filter "dangling=true" -q --no-trunc)

run: ## Run command in the container
	$(docker-app-exec) '$(filter-out $@,$(MAKECMDGOALS))'

help: # Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.DEFAULT_GOAL := help
