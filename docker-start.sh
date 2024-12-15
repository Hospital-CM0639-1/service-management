docker exec --workdir /var/www/html management_service composer install --no-interaction --optimize-autoloader
docker exec --workdir /var/www/html management_service php bin/console secrets:decrypt-to-local --force --env=dev
docker exec --workdir /var/www/html management_service composer dump-env dev
docker exec --workdir /var/www/html management_service php bin/console lexik:jwt:generate-keypair
docker exec --workdir /var/www/html management_service php bin/console doctrine:migrations:migrate --no-interaction
docker exec --workdir /var/www/html management_service php bin/console cache:clear --quiet
docker exec --workdir /var/www/html management_service php bin/console c:w --quiet
