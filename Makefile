# vim: set tabstop=8 softtabstop=8 noexpandtab:
phpstan:
	symfony php vendor/bin/phpstan analyse --configuration phpstan.neon.dist --generate-baseline=phpstan-baseline.neon --no-progress

phpstan-baseline:
	symfony php vendor/bin/phpstan analyse --configuration phpstan.neon.dist --no-progress

cs:
	symfony php vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --diff --verbose

test:
	php vendor/bin/phpunit -v
