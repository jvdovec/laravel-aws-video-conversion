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
Please continue [here](resources/docs/development-environment-initialisation.md).


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
