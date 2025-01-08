fix:
	./vendor/bin/pint

install:
	composer install

db-migrate:
	php artisan migrate

db-rollback:
	php artisan migrate:rollback

serve:
	php artisan serve
