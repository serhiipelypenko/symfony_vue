NODE_MODULES = ./node_modules
VENDOR = ./vendor

##
## UTILS
## ---------
watch:
	npm run watch

##
## REFACTORING
## ----------

check:
	make refactoring --keep-going

refactoring: eslint php-cs-fixer

eslint:
	${NODE_MODULES}/.bin/eslint assets/js/ --ext .js,.vue --fix

php-cs-fixer:
	${VENDOR}/bin/php-cs-fixer fix src --verbose

phpstan:
	${VENDOR}/bin/phpstan
