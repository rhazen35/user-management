run:
	docker-compose pull
	docker-compose up --force-recreate

bash:
	docker-compose exec php-fpm bash

install:
	docker-compose exec php-fpm composer install

migrations:
	docker-compose exec php-fpm bin/console do:mi:mi -n

reset-db-with-migrations:
	docker-compose exec php-fpm bin/console do:da:dr --force --if-exists
	docker-compose exec php-fpm bin/console do:da:cr

	make migrations
