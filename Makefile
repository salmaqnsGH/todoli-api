fix:
	./vendor/bin/pint

install:
	composer install

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
