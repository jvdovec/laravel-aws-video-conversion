# Usage of the development environment

## Laravel Sail

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

## PHP Coding Standards Fixer

You can run this coding standards fixer by calling bash script alias:

```sh
phpcsfixer
```
