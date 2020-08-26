<h3 align="center">Laravel AWS Video Conversion</h3>
<p>
Basic working example for usage Amazon Web Services for cloud video conversion
</p>

<!-- TABLE OF CONTENTS -->
## Table of Contents

* [Built With](#built-with)
* [Getting Started](#getting-started)
  * [Prerequisites](#prerequisites)
  * [Local Environment Initialisation](#local-environment-initialisation)
    * [Harbor (Docker way - MacOS / Linux)](#harbor-docker-way-for-macos-or-linux)
    * [Windows](#windows)
  * [Amazon Web Services setup](#amazon-web-services-setup)
* [Usage](#usage)

## Built With

* [Laravel](https://laravel.com/)
* [Harbor wrapper for Docker](https://github.com/BRACKETS-by-TRIAD/harbor-laravel)

## Getting Started

To get a local copy up and running follow these simple steps.

### Prerequisites

* MacOS / Linux with Docker installed - you can utilize provided Docker wrapper (Harbor) for running your local environment - **recommended way** OR fully working environment which satisfies requirements for [Laravel](https://laravel.com/docs/7.x#server-requirements) 
* Amazon AWS credentials
 
### Local Environment Initialisation

1. Clone the repo
```sh
git clone https://github.com/JanciVd/laravel-aws-video-conversion.git
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

### Amazon Web Services setup

You need to get and fill these environmental variables in your `.env` file
```
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
```

## Usage

### Harbor (Docker way - MacOS / Linux)

1. Start local environment
```sh
./harbor start
```

Your app will be reachable at http://localhost/

2. Stop local environment
```sh
./harbor stop
```
