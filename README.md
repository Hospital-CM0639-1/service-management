# MANAGEMENT SERVICE

### Setup
1. Download "dev.decrypt.private.php" key from keys-repo and put it inside of config/secrets/dev
2. Eventually, you can change docker port inside of docker-composer.yaml 
3. Launch the setup with:
    ```sh
    docker compose -f PATH_TO_REPO/docker-compose.yaml -p service-management up -d
    ```
4. Execute PATH_TO_REPO/docker-start.sh to install dependencies and execute migrations (I suggest to execute them every time after a pull)
   NB: DO NOT EXECUTE MIGRATIONS IF YOU HAVE EXECUTE SCRIPT IN DATABASE REPOSITORY
5. Check .env.dev.local and .env.local.php to see if DATABASE_ params are correct, otherwise change in both files.
6. The service is reachable to http://127.0.0.1:DOCKER_PORT
7. Call http://127.0.0.1:DOCKER_PORT/login in POST with following json body: {"username": "admin", "password": "admin"}



### API Call
1. Create an api user into the users table (type_id check user_types table) with random values, it's just labels
2. Execute the following command:
   ```sh
   docker exec --workdir /var/www/html service-management php bin/console api:generate-api-token --user-id=INSERT_USER_ID --name=INSERT_API_TOKEN_NAME
   ```
3. The given token must be inserted in the requests to "api/gateway/validate-user-token" and "api/service/validate-user-token" endpoints under header name "API-Token"
