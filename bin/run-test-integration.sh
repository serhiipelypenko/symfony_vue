#!/usr/bin/env sh
export APP_ENV=test
echo $APP_ENV
symfony console doctrine:schema:drop --force
symfony console doctrine:schema:create
symfony console doctrine:schema:update --force
symfony console hautelook:fixtures:load -n

symfony php ./vendor/bin/phpunit --testdox --group integration
