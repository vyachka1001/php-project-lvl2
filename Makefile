install:
	composer install

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src tests

validate:
	composer validate

update:
	composer update

test:
	composer exec --verbose phpunit tests

test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml