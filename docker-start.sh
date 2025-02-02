docker exec --workdir /var/www/html service-management composer install --no-interaction --optimize-autoloader
docker exec --workdir /var/www/html service-management php bin/console secrets:decrypt-to-local --force --env=dev
docker exec --workdir /var/www/html service-management composer dump-env dev
# NOT EXECUTE THIS: docker exec --workdir /var/www/html service-management php bin/console lexik:jwt:generate-keypair

# THE FOLLOWING LINE IT'S NOT NECESSARY IF YOU'VE EXECUTED SCRIPT IN DATABASE REPOSITORY
docker exec --workdir /var/www/html service-management php bin/console doctrine:migrations:migrate --no-interaction

docker exec --workdir /var/www/html service-management php bin/console cache:clear --quiet
docker exec --workdir /var/www/html service-management php bin/console c:w --quiet
