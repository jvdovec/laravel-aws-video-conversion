# Development environment initialisation

1. Clone the repo
```sh
git clone https://github.com/jvdovec/laravel-aws-video-conversion.git
```

## Laravel Sail (Docker environment)

2. Make sure no other Docker containers are running
```sh
docker ps
```

3. First installation of vendor packages (including Laravel Sail) - one-time only, please run following command in the root of the cloned repository
```sh
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

4. Create .env file from template:
```sh
cp .env.example .env
```

5. Generate application key:
```sh
./vendor/bin/sail artisan artisan key:generate --ansi
```
