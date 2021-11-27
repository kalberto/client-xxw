##Client XXW

## Tecnology

- PHP 7.1.8
- Framework PHP Laravel 5.8
- MYSQL Database
- HTML5, CSS3, Javascript
- VueJS

### Required dependencies

- PHP >= 7.1.8
- Postgres > 9.3
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- Apache Server
- MYSQL Server
- Java JDK 1.8

### Installation Instructions

```
git clone https://<user>@repository.git

cp .env.example .env

Edit database configuration, Smtp configuration and etc.

npm install

composer install

php artisan key:generate

php artisan cache:clear
```

### Database

```
php artisan migrate
php artisan db:seed

On database: CREATE EXTENSION unaccent;
```

### Passport
    composer require laravel/passport
    php artisan migrate
    php artisan passport:install
    php artisan passport:client --personal
     What should we name the personal access client? = user_auth_token

### External libraries
    FFMPEG BINARIES thumb generation
    config/thumbnail.php
        'ffmpeg'  => env('FFMPEG_PATH', base_path('path')),
        'ffprobe' => env('FFPROBE_PATH', base_path('path')),

### Authors

* **Alberto de Almeida Guilherme - *Backend Developer*** 
* **Marco - *Frontend Developer*** 
* **Samuel - *Frontend Developer*** 
