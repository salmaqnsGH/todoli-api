fix:
	./vendor/bin/pint

install:
	composer install

migration:
	@if [ -z "$(name)" ]; then \
		echo "Please provide a migration name. Usage: make migration name=create_products_table"; \
		exit 1; \
	fi
	php artisan make:migration $(name)

model:
	@if [ -z "$(name)" ]; then \
		echo "Please provide a model name. Usage: make model name=Product"; \
		exit 1; \
	fi
	php artisan make:model $(name)

model-m:
	@if [ -z "$(name)" ]; then \
		echo "Please provide a model name. Usage: make model name=Product"; \
		exit 1; \
	fi
	php artisan make:model $(name) -m

controller:
	@if [ -z "$(path)" ]; then \
		echo "Please provide a controller path. Usage: make controller path=Api/Auth/AuthController"; \
		exit 1; \
	fi
	php artisan make:controller $(path)

controller-s:
	@if [ -z "$(path)" ]; then \
		echo "Please provide a path. Usage: make controller-s path=Api/Auth/AuthController"; \
		exit 1; \
	fi
	@echo "Creating controller..."
	php artisan make:controller $(path)
	@echo "Creating service..."
	@mkdir -p app/Services/$(dir $(path))
	$(eval SERVICE_NAME=$(shell echo $(notdir $(basename $(path))) | sed 's/Controller//'))
	@echo "<?php\n\nnamespace App\\Services\\$(subst /,\\,$(dir $(path)));\n\nclass $(SERVICE_NAME)Service\n{\n\n}" > app/Services/$(dir $(path))$(SERVICE_NAME)Service.php
	@echo "Created service at app/Services/$(dir $(path))$(SERVICE_NAME)Service.php"

request:
	@if [ -z "$(path)" ]; then \
		echo "Please provide a request path. Usage: make request path=Api/Auth/LoginRequest"; \
		exit 1; \
	fi
	php artisan make:request $(path)

provider:
	@if [ -z "$(name)" ]; then \
		echo "Please provide a provider name. Usage: make provider name=PermissionServiceProvider"; \
		exit 1; \
	fi
	php artisan make:provider $(name)

middleware:
	@if [ -z "$(name)" ]; then \
		echo "Please provide a middleware name. Usage: make middleware name=EnsureTokenIsValid"; \
		exit 1; \
	fi
	php artisan make:middleware $(name)

db-seed:
	php artisan db:seed

db-migrate:
	php artisan migrate

db-rollback:
	php artisan migrate:rollback

serve:
	php artisan serve

clear:
	php artisan optimize:clear

link-storage:
	php artisan storage:link

queue-listen:
	php artisan queue:listen

queue-work:
	php artisan queue:work

queue-flush:
	php artisan queue:clear && php artisan queue:flush

husky-init:
	chmod +x .husky/*
	chmod +x .husky/_/*
