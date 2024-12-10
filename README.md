# MANAGEMENT SERVICE

### Setup
1. Download "dev.decrypt.private.php" key from keys-repo and put it inside of config/secrets/dev
2. Eventually, you can change docker port inside of docker-composer.yaml 
3. Launch the setup with:
    ```sh
    docker compose -f PATH_TO_REPO/docker-compose.yaml -p management_service up -d
    ```
4. Execute PATH_TO_REPO/docker-start.sh to install dependencies and execute migrations 
5. The service is reachable to http://127.0.0.1:DOCKER_PORT
6. Call http://127.0.0.1:DOCKER_PORT/login in POST with following json body: {"username": "admin", "password": "admin"}