install:
	composer install

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin tests

validate:
	composer validate

update:
	composer update

test:
	composer exec --verbose phpunit tests