<h3 align="center">Laravel AWS Video Conversion</h3>
<p style="text-align: center;">
Basic working example for usage Amazon Web Services for cloud video conversion
</p>

## Table of Contents

* [Built With](#built-with)
* [Prerequisites](#prerequisites)
* [Getting Started](#getting-started)
  * [Development environment initialisation](#development-environment-initialisation)
  * [Usage of the development environment](#usage-of-the-development-environment)
  * [Amazon Web Services setup](#amazon-web-services-setup)

## Built With

* [Laravel](https://laravel.com/)
* [Laravel Sail for local development through docker](https://laravel.com/docs/9.x/sail)

## Prerequisites

* MacOS / Linux with Docker installed 
* Amazon AWS credentials

## Getting Started

To get a local copy up and running follow these simple steps.


 
### Development environment initialisation

1. Clone the repo
```sh
git clone https://github.com/jvdovec/laravel-aws-video-conversion.git
```

#### Laravel Sail (Docker environment)

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

### Usage of the development environment

1. Start local environment in detached mode
```sh
./vendor/bin/sail up -d
```

Your app will be reachable at http://localhost/

2. Stop local environment
```sh
./vendor/bin/sail stop
```

3. (Optional) Create bash alias for sail
-> https://laravel.com/docs/9.x/sail#configuring-a-bash-alias

### Amazon Web Services Setup

Please continue [here](resources/docs/aws-services-setup.md).
