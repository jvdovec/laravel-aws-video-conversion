<h3 align="center">Laravel AWS Video Conversion</h3>
<p style="text-align: center;">
Basic working example for usage Amazon Web Services for cloud video conversion
</p>

## Table of Contents

* [Built With](#built-with)
* [Prerequisites](#prerequisites)
* [Getting Started](#getting-started)
  * [Development Environment Initialisation](#development-environment-initialisation)
    * [Harbor (Docker way - MacOS / Linux)](#harbor-docker-way-for-macos-or-linux)
    * [Windows](#windows)
  * [Usage of the Development Environment](#usage-of-the-development-environment)
  * [Amazon Web Services setup](#amazon-web-services-setup)

## Built With

* [Laravel](https://laravel.com/)
* [Harbor wrapper for Docker](https://github.com/BRACKETS-by-TRIAD/harbor-laravel)

## Prerequisites

* MacOS / Linux with Docker installed - you can utilize provided Docker wrapper (Harbor) for running your local environment - **recommended way** OR fully working environment which satisfies requirements for [Laravel](https://laravel.com/docs/7.x#server-requirements) 
* Amazon AWS credentials

## Getting Started

To get a local copy up and running follow these simple steps.


 
### Development Environment Initialisation

1. Clone the repo
```sh
git clone https://github.com/jvdovec/laravel-aws-video-conversion.git
```

#### Harbor (Docker way for MacOS or Linux)

2. Make sure no other Docker containers are running
```sh
docker ps
```
3. Initialise Harbor (one-time only)
```sh
./harbor init
```
That will set some .env variables, will prepare Docker containers, will prepare Laravel (app key + migrations)

#### Windows

1. Create a copy of `.env.example` to new file `.env`
1. Update .env file to suit your local environment
2. Run Laravel init commands
```sh
php artisan key:generate & php artisan migrate
```

### Usage of the development environment

#### Harbor (Docker Way - MacOS / Linux)

1. Start local environment
```sh
./harbor start
```

Your app will be reachable at http://localhost/

2. Stop local environment
```sh
./harbor stop
```

### Amazon Web Services Setup

Please continue [here](resources/docs/aws-services-setup.md).
